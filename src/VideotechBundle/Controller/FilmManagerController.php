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

    /* Display a survey */
    public function displaySurveyPageAction($sId){
        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        // Retrive survey
        $survey = $em->getRepository('BigDataSurveyBundle:Survey')
                   ->find($sId);

        // Retrive all exsisting categories
        $categories = $em->getRepository('BigDataSurveyBundle:Category')
                   ->findAll();

        // Render display
        return $this->render('BigDataSurveyBundle:Admin:SurveyManager/display_survey.html.twig', array(
            "survey" => $survey,
            "categories" => $categories
        ));
    }


    // Create actions /////////////////////////////////////////////////////////
    
    /* create a new survey */
    public function createFilmAction()
    {
        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        //Create new survey
        $film = new Film();
        
        // Retrive category
        $category = $em->getRepository('VideotechBundle:Category')
                   ->find($_POST['categorySelecter']);

        // Set main info
        $film->setTitle($_POST['title']);
	$film->setDescription($_POST['description']);
        $film->setCategory($category);

        // Upload file containing main code to survey
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

    /* Update survey action */
    public function updateSurveyAction($sId){
        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        // Retrive survey
        $survey = $em->getRepository('BigDataSurveyBundle:Survey')
                   ->find($sId);
        
        // Retrive category
        $category = $em->getRepository('BigDataSurveyBundle:Category')
                   ->find($_POST['categorySelecter']);

        // Set main info
        $survey->setName($_POST['name']);
        $survey->setCategory($category);

        // Upload file containing main code to survey
        if (is_uploaded_file($_FILES['code']['tmp_name'])) {
            $dataToSurvey = new DataToSurvey();
            $dataCode = new DataCode();
            $dataToSurvey->setType('code');
            $dataCode->setCode(file_get_contents($_FILES['code']['tmp_name']));
            $dataCode->setDataToSurvey($dataToSurvey);
            $em->persist($dataCode);
            $dataToSurvey->setTitle($_FILES['code']['name']);
            $em->persist($dataToSurvey);
            $survey->setMainDataToSurvey($dataToSurvey);
        }

        // For each annex file upload it
        foreach ($_FILES['annexCode']['tmp_name'] as $key => $annexCodeFile) {
            if (is_uploaded_file($annexCodeFile)) {
                $dataToSurvey = new DataToSurvey();
                $dataCode = new DataCode();
                $dataToSurvey->setType('code');
                $dataCode->setCode(file_get_contents($annexCodeFile));
                $dataCode->setDataToSurvey($dataToSurvey);
                $em->persist($dataCode);
                $dataToSurvey->setTitle($_FILES['annexCode']['name'][$key]);
                $em->persist($dataToSurvey);
                $survey->addAnnexData($dataToSurvey);
            }
        }

        // alert doctrine to follow this object
        $em->persist($survey);

        // Execute pending DB operations
        $em->flush();

       // redirect to the survey list route
        return $this->redirectToRoute('bds_admin_survey_list_page');
        
    }

    // Delete actions /////////////////////////////////////////////////////////

    /* delete survey action */
    public function deleteSurveyAction($sId){
        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        // Retrive survey
        $survey = $em->getRepository('BigDataSurveyBundle:Survey')
                   ->find($sId);

            // Check if found
        if ($survey) {
            // Set to be removed !!!! Cascade actions automaticly launched by Doctrine
            $em->remove($survey);

            // Save changes to DB
            $em->flush();
           
            $responseArray = array(
                'response' => 'success'
            );
        } else {
            $responseArray = array(
                'response' => 'error',
                'message' => "Survey Doesn't exsist !"
            );
        }

        // Render a respons containing JSON data (not HTML or anything else)
        return new JsonResponse($responseArray);
    }


    
    

    
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




    /* Add a new question 
     * This controller action is addapted to the Question modal 
     * it will send back a JSON
     */
    public function modalCreateCategoryAction()
    {

        // Get entity Manager
        $em = $this->get('doctrine')->getManager();

        // Create new category
        $category = new Category();

        // Set question text
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
