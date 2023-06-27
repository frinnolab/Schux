<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model("posts_m");
        $this->load->model("posts_categories_m");
        $this->load->model("posts_category_m");
    }

    public function index_get() 
    {
        $this->retdata['posts_categorys'] = pluck_multi_array($this->posts_category_m->get_order_by_posts_category(), 'posts_categoriesID', 'postsID');   
        $this->retdata['posts_categories'] = pluck($this->posts_categories_m->get_order_by_posts_categories(), 'posts_categories', 'posts_categoriesID');
        $this->retdata['posts'] = $this->posts_m->get_order_by_posts();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
