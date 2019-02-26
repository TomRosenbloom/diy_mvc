<?php

//namespace App;

class EventController extends DomainModelController
{
    /**
     * set model identifier in constructor
     */
    public function __construct()
    {
        parent::__construct("Event"); // have I used 'value object' here?
    }

    public function index()
    {
        $this->view('event/index','');
    }
    
    public function show_all_with_search()
    {
        $event = $this->model; // (pointless) assignment to change var name hence make code clearer
        
        // send event data to view as json for map and array for list
        // ALL events for display before form submission
        $data['events_arr'] = $this->model->getAll();
        $data['events_json'] = json_encode($data['events_arr']);
        
        // send list of event feeds (aka source, type) for select
        $feed = new Feed;
        $data['feeds'] = $feed->getAll();

        if($this->getRequest()->getMethod() === 'POST') {
            $post_data = $this->getRequest()->getPostVars();
            $rangeKm = intval($post_data['range']);
            $postcode = $post_data['postcode']; // VALIDATION!!
            $feed_id = $post_data['feed'];
            $clear = $post_data['clear'] ?? null;

            if($clear){
                $data['events_arr'] = $this->model->getAll();
                $post_data = [];
            } else {
                $postcodeRange = new PostcodeRange($postcode, $rangeKm);
                $data['events_arr'] = $event->getFiltered($postcodeRange, $feed_id);                    
            }
            
            $data['events_json'] = json_encode($data['events_arr']);

            // send post vars back to view for display in form
            $data['post_vars'] = $post_data;
            
            // if an origin feed was selected as a filter, get its name and return for display in the view
            $data['feed_name'] = $feed->getOneFromId($feed_id)['name'];
            
        }

        $this->view('event/show_all_with_search', $data);   
    }


    public function show_all()
    {
        $event = $this->model;
        $data['events_arr'] = $event->getAll();
        $data['events_json'] = json_encode($data['events_arr']);

        $this->view('event/show_all', $data);
    }


    public function show_one($id = '')
    {
        $id = 1;
        $event = new Event();
        $data = $event->getOneFromId($id);

        $this->view('event/show_one', $data);
    }
}
