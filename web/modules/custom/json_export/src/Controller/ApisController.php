<?php

namespace Drupal\json_export\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\user\Entity\User;
use Drupal\views\Views;
use Drupal\Core\Url;

class ApisController extends ControllerBase
{

  /**
   * Handel response for the api/pages
   * We use 5 views to render the data nested
   * 1- Get all pages
   * 2- Get posts loaders for each page
   * 3- Get Posts contents for each post
   * 4- Get Galleries for each post
   * 5- Get Gallery image and contnet for each gallery
   */
  public function getPages(Request $request)
  {
    $return = new \stdClass();

    try{

      $return->message = 'Successfully Retrieved';
      /**
       * 1- Get all pages 
       * http://d9meat.local.com/admin/structure/views/view/api_pages_list_rest
       */
      $result = $this->handelInnerData('api_pages_list_rest','rest_export_1',null);
      foreach($result as $k => $v)
      {
        /**
         * 2- Get posts loaders for each page
         * http://d9meat.local.com/admin/structure/views/view/api_page_posts_list_rest
         */
        $args = $result[$k]['field_ctf_pages_osts_pgt_posts'];
        $result_posts = $this->handelInnerData('api_page_posts_list_rest','rest_export_1',$args);
        // Handel inner array
        $result[$k]['field_ctf_pages_osts_pgt_posts_result'] = $result_posts;

        foreach($result_posts as $kp => $vp)
        {
          /**
           * 3- Get Posts contents for each post
           * http://d9meat.local.com/admin/structure/views/view/field_pgtf_posts_loader_ref_post
           */
          $args = $result_posts[$kp]['field_pgtf_posts_loader_ref_post'];
          $result_posts_contents = $this->handelInnerData('api_page_posts_content_list_rest','rest_export_1',$args);
          // Handel inner array
          $result[$k]['field_ctf_pages_osts_pgt_posts_result']
          [$kp]['field_pgtf_posts_loader_ref_post_result'] = $result_posts_contents;

          foreach($result_posts_contents as $kpc => $vpc)
          {
            /**
             * 4- Get Galleries for each post
             * http://d9meat.local.com/admin/structure/views/view/api_page_posts_content_img_list_rest
             */
            $args = $result_posts_contents[$kpc]['field_ctf_posts_pgt_gallery'];
            $result_posts_content_imgs = $this->handelInnerData('api_page_posts_content_img_list_rest','rest_export_1',$args);
            // Handel inner array
            $result[$k]['field_ctf_pages_osts_pgt_posts_result']
            [$kp]['field_pgtf_posts_loader_ref_post_result']
            [$kpc]['field_ctf_pages_osts_pgt_posts_result'] = $result_posts_content_imgs;

            foreach($result_posts_content_imgs as $kpci => $vpci)
            {
              /**
               * 5- Get Gallery image and contnet for each gallery
               * http://d9meat.local.com/admin/structure/views/view/api_page_posts_content_img_list_rest
               */
              $args = $result_posts_content_imgs[$kpci]['field_pgtf_image_gallery_attach'];
              $result_posts_content_images = $this->handelInnerData('api_page_posts_content_imgs_list','rest_export_1',$args);
              // Handel inner array
              $result[$k]['field_ctf_pages_osts_pgt_posts_result']
              [$kp]['field_pgtf_posts_loader_ref_post_result']
              [$kpc]['field_ctf_pages_osts_pgt_posts_result']
              [$kpci]['field_ctf_pages_osts_pgt_posts_result'] = $result_posts_content_images;

              // and more more nested!!

            }
          }
        }
      }
      // dd($result);
      $return->result  = $result;
      //TODO Pagination
      // $return->pagination = $pagination;
      return new JsonResponse($return ,Response::HTTP_OK);

    } catch (\Throwable $ex) {
      $return->message = $ex->getMessage();
      return new JsonResponse($return ,Response::HTTP_BAD_REQUEST);
    }
    catch(\Exception $ex)
    {
      $return->message = $ex->getMessage();
      return new JsonResponse($return ,Response::HTTP_BAD_REQUEST);
    }
    
  }

  /**
   * Execute the views and parse the result
   */
  public function handelInnerData($view_name,$display_name,$args)
  {
    $data_list = Views::getView($view_name);
    $build_date = $data_list->buildRenderable($display_name, [$args]);
    $data_result = render($build_date);
    $contents_data = json_decode($data_result,true);
    return $contents_data;
  }



}
