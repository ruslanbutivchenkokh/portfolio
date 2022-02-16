<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Commands\ApiCommand;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends AbstractController
{
    /**
     * @Route("/booking", name="booking_index", )
     */
    public function index()
    {
        return $this->redirectToRoute('index', [], 301);
    }

    /**
     * @Route("/booking/{token}", name="booking_token")
     */
    public function booking($token, Request $request)
    {
        // GETTING A TOKEN
    	$token = urlencode($token);
        // initialization command
        $api = new ApiCommand( 'https://portal.palletways.com/api/bookin_process.php?token=', $token );
        // check if form submit
        if(!empty($request->request->all())) {

        	$form_data = $request->request->all();

            // change the format of the start and end date
        	$avaliable_dates = explode('|', $form_data['selected_date']);
        	$form_data['selected_date'] = array('from_timestamp' => trim($avaliable_dates[0]), 'to_timestamp' => trim($avaliable_dates[1]));

        	$parameters = array(
        		'body' => $form_data
        	);

            // send form data to server
            $api->createRequest('POST', $parameters);

            if($api->getStatusCode() == 200) {
                return $this->redirectToRoute('booking_success');
            } else {
                throw new \Exception('Server is not available');
            }
        }

        // send request to server 
        $api->createRequest('GET');
        // getting data for delivery
        $data = $api->getResponceData();

        if(isset($data['ERROR'])) {
            return $this->render('default/token_error.html.twig', [
                'error_message' => $data['ERROR']
            ]);
        }

        // random image from page
        $image_array = array(
            'peoples1.jpg',
            'peoples2.jpg',
            'peoples3.jpg',
            'peoples4.jpg',
        );

        $random_int = array_rand($image_array, 1);

        return $this->render('booking/booking.html.twig', [
            'user_data' => $data,
            'main_image'=> $image_array[$random_int],
            'js'        => array(
                'library/jquery-payment/jquery.payment.js',
                'js/pages/booking/booking.js'
            )
        ]);
    }

    /**
     * @Route("/booking/buy_slot", priority="10", name="booking_buy_slot" )
     */
    public function buySlot(Request $request)
    {

        if( !empty($request->request->all()) ) {
            /*
             * In this lines should be a
             * WORKER FOR PAYMENT
             */

        }
        return new Response(json_encode(
            array(
                'status' => true
            )
        ),200, ['content-type' => 'application/json']);
    }

    /**
     * @Route("/booking/success", priority="10", name="booking_success" )
     */
    public function bookingSuccess()
    {
        return $this->render('booking/success.html.twig');
    }
}