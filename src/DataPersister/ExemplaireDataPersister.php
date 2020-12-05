<?php
namespace App\DataPersister;

use App\Entity\BlogPost;
use App\Entity\Exemplaire;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Repository\ExemplaireRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ExemplaireDataPersister implements DataPersisterInterface
{
    /**
     * Entity Manager
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Exemplaire Repository
     *
     * @var ExemplaireRepository
     */
    private $exemplaireRepository;

    public function __construct(EntityManagerInterface $em, ExemplaireRepository $exemplaireRepository)
    {
        $this->em = $em;
        $this->exemplaireRepository = $exemplaireRepository;
    }

    public function supports($data): bool
    {
        return $data instanceof Exemplaire;
    }

    public function persist($data)
    {
       
      //Si l'exemplaire vient d'être crée par un utilisateur et donc n'a pas de numéro d'exemplaire....
      if($data->getNumExemplaire() === NULL)
      {
        $livreId = $data->getLivre()->getId();
        
        $lastExemplaireOfTheBook = $this->em->getRepository(Exemplaire::class)->findLastExemplaireOfTheBook($livreId);
        
        $lastIdExemplaireOfTheBook = $lastExemplaireOfTheBook[0]['numExemplaire'];

        $data->setNumExemplaire($lastIdExemplaireOfTheBook + 1);
      }

      $this->em->persist($data);
      $this->em->flush($data);
      //call your persistence layer to save $data
      return $data;
    }

    public function remove($data)
    {
      $this->em->remove($data);
      $this->em->flush($data);
    }
}