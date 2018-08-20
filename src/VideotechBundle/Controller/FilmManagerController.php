<?php

namespace VideotechBundle\Controller;

// Includes /////////////////////////////////////////////////////////////////// 
// Symfony objects //
// Base constroler
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
// JSON respons 
use Symfony\Component\HttpFoundation\JsonResponse;

// Doctrine //
use Doctrine\ORM\EntityManagerInterface;

// Enteties //
use VideotechBundle\Entity\Category;
use VideotechBundle\Entity\Film;


///////////////////////////////////////////////////////////////////////////////
//                           FilmManager controller                          //
//           This controller manages the categories and the films.           //
///////////////////////////////////////////////////////////////////////////////
class FilmManagerController extends Controller
{


    ///////////////////////////////////////////////////////////////////////////
    //                              Film actions                             //
    ///////////////////////////////////////////////////////////////////////////
    
    // Display page Actions////////////////////////////////////////////////////
    
    /* Displays the page to create a new film */
    public function createFilmPageAction()
    {
        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        // Retrive all exsisting categories
        $categories = $em->getRepository('VideotechBundle:Category')
                   ->findAll();
        $nbFilm = count($em->getRepository('VideotechBundle:Film')
                   ->findAll());

        // Render display
        return $this->render('@Videotech/Film/create_film.twig', array(
            "categories" => $categories,
	    "nbFilm" => $nbFilm
        ));
    }

    /* Display a Film */
    public function displayFilmPageAction($catName, $filmName, $filmId){
        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        $nbFilm = count($em->getRepository('VideotechBundle:Film')
                   ->findAll());

	$film = $em->getRepository('VideotechBundle:Film')
                   ->findOneById($filmId);

        // Retrive all exsisting categories
        $categories = $em->getRepository('VideotechBundle:Category')
                   ->findAll();

        // Render display
        return $this->render('@Videotech/Film/film.twig', array(
            "film" => $film,
	    "categories" => $categories,
	    "nbFilm" => $nbFilm
        ));
    }


    // Create actions /////////////////////////////////////////////////////////
    
    /* create a new Film */
    public function createFilmAction()
    {
        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        //Create new Film
        $film = new Film();
        
        // Retrive category
        $category = $em->getRepository('VideotechBundle:Category')
                   ->find($_POST['categorySelecter']);

        // Set main info
        $film->setTitle($_POST['title']);
	$film->setDescription($_POST['description']);
        $film->setCategory($category);

        // Upload file
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
	   $image = new UploadedFile($_FILES['image']['tmp_name'], $_FILES['image']['name']);
           $film->setImageFile($image);
	   $film->setImageName($_FILES['image']['name']);
	   $film->setImageSize(filesize($_FILES['image']['tmp_name']));
        }


        // alert doctrine to follow this object
        $em->persist($film);

        // Execute pending DB operations
        $em->flush();

       // redirect to the survey list route
        return $this->redirectToRoute('videotech_homepage');
    }


    // Save and update actions/////////////////////////////////////////////////

    /* Update Film action */
    public function updateFilmAction($Id){
             // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        //get Film
        $film = $em->getRepository('VideotechBundle:Film')
                   ->findOneById($Id);
        
        // Retrive category
        $category = $em->getRepository('VideotechBundle:Category')
                   ->find($_POST['categorySelecter']);

        // Set main info
        $film->setTitle($_POST['title']);
	$film->setDescription($_POST['description']);
        $film->setCategory($category);

        // Upload file 
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
	   $image = new UploadedFile($_FILES['image']['tmp_name'], $_FILES['image']['name']);
           $film->setImageFile($image);
	   $film->setImageName($_FILES['image']['name']);
	   $film->setImageSize(filesize($_FILES['image']['tmp_name']));
        }


        // alert doctrine to follow this object
        $em->persist($film);

        // Execute pending DB operations
        $em->flush();

       // redirect to the survey list route
        return $this->redirectToRoute('videotech_homepage');
        
    }

    // Delete actions /////////////////////////////////////////////////////////

    

    
    ///////////////////////////////////////////////////////////////////////////
    //                            Category actions                           //
    ///////////////////////////////////////////////////////////////////////////

   // Display page Actions////////////////////////////////////////////////////
    
    /* Displays the Categories */
    public function categoriesPageAction()
    {
        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        // Retrive all exsisting categories
        $categories = $em->getRepository('VideotechBundle:Category')
                   ->findAll();

	$nbFilm = count($em->getRepository('VideotechBundle:Film')
                   ->findAll());

        // Render display
        return $this->render('@Videotech/Category/list.twig', array(
            "categories" => $categories,
	    "nbFilm" => $nbFilm
        ));
    }



    
    /* Displays the Category */
    public function categoryPageAction(Request $request, $name)
    {
        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        // Retrive all exsisting categories
        $category = $em->getRepository('VideotechBundle:Category')
                   ->findOneByName($name);

	$films = $category->getFilms();


    	$paginator  = $this->get('knp_paginator');
    	$pagination = $paginator->paginate(
        	    $films,
		    $request->query->getInt('page', 1)/*page number*/
		    );

	$nbFilm = count($em->getRepository('VideotechBundle:Film')
                   ->findAll());

        // Render display
        return $this->render('@Videotech/Category/listFilm.twig', array(
	    "catName" => $name,
            'pagination' => $pagination,
	    "nbFilm" => $nbFilm
        ));
    }

    // Create actions ///////////////////////////////////////////////////////// 




    /* Add a new category 
     * This controller action is addapted to the category modal 
     * it will send back a JSON
     */
    public function modalCreateCategoryAction()
    {

        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        // Create new category
        $category = new Category();

        // Set category namet
        $category->setName($_POST['categoryName']);
        
        // Alert doctrine that it will be saved to DB
        $em->persist($category);
        // Save all doctrine pending data to DB
        $em->flush();

        // Success response info
        $responseArray = array(
            'response' => 'success',
            'data' => array(
                'id' => $category->getId(),
                'text' => $category->getName()
            )
        );

        // Render a respons containing JSON data (not HTML or anything else=
        return new JsonResponse($responseArray);
    }

}
