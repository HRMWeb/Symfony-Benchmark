<?php

namespace Hrmweb\BenchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Hrmweb\BenchBundle\Entity\Bench;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BenchController extends Controller
{
    /**
     * @Route("/bench/plain", name="_bench_plain")
     * @Template()
     */
    public function plainAction()
    {
        error_log("Memory used by plain action: " . memory_get_peak_usage());
        return array();
    }

    /**
     * @Route("/bench/model", name="_bench_model")
     * @Template()
     */
    public function modelAction()
    {
        for ($i = 0; $i < 100; $i++) {
            foreach ($product = $this->getDoctrine()
                ->getRepository('HrmwebBenchBundle:Bench')
                ->findAll() as $b) {
                error_log($b->getTitle());
            }
        }
        for ($i = 0; $i < 10; $i++) {
            $b = new Bench();
            $b->setTitle('test bench');
            $em = $this->getDoctrine()->getManager();
            $em->persist($b);
            $em->flush();
            $em->remove($b);
            $em->flush();
        }        
        error_log("Memory used by model action: " . memory_get_peak_usage());
        return array();
    }
}
