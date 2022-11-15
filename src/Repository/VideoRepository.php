<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Video>
 *
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function save(Video $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Video $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function displayAllVideos(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT v
            FROM App\Entity\Video v
            '
        );

        return $query->getResult();

    }
    public function displayAllVideosNbLikes(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT v
            FROM App\Entity\Video v
            ORDER BY v.nb_likes DESC
            '
        );

        return $query->getResult();

    }

    function setLinkForYtVideo(string $link) : string
    {
        $newLink = str_replace('watch?v=', 'embed/', $link);
        return $newLink;
    }

    public function displayAllVideosByCategory(string $category): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT v
            FROM App\Entity\Video v
            WHERE v.category = :category
            ORDER BY v.nb_likes DESC
            '
        )->setParameter('category',$category);

        return $query->getResult();

    }

    public function deleteVideo(int $idVideo)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'DELETE
            FROM App\Entity\Video v
            WHERE v.id = :id
            '
        )->setParameter('id',$idVideo);

        $query->getResult();

    }


//    /**
//     * @return Video[] Returns an array of Video objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Video
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
