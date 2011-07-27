<?php
class eu_urho_apartments_controllers_apartment
{
    public function __construct(midgardmvc_core_request $request)
    {
        $this->request = $request;
        $this->mvc = midgardmvc_core::get_instance();
        eu_urho_apartments_utils::set_language();
    }

    /**
     * Retrieves an apartment from the database
     *
     * @param array HTTP request arguments
     */
    public function get_apartment(array $args)
    {
        $this->data['apartment'] = $this->get_apartment_by_name($args['apartment']);
        $this->data['apartment']->rdfmapper = new midgardmvc_ui_create_rdfmapper($this->data['apartment']);

        // fetch default features
        $this->data['features'] = $this->retrieve_features();
#var_dump($this->data['features']);
#die;

        midgardmvc_core::get_instance()->head->set_title($this->data['apartment']->title);

        midgardmvc_core::get_instance()->head->enable_jquery();

        midgardmvc_core::get_instance()->head->add_link
        (
            array
            (
                'rel' => 'stylesheet',
                'type' => 'text/css',
                'href' => MIDGARDMVC_STATIC_URL . '/eu_urho_apartments/css/stack_article.css'
            )
        );

        midgardmvc_core::get_instance()->head->add_jsfile(MIDGARDMVC_STATIC_URL . '/eu_urho_apartments/js/menu.js');
        midgardmvc_core::get_instance()->head->add_jsfile(MIDGARDMVC_STATIC_URL . '/eu_urho_apartments/js/downloadmenu.js');
        midgardmvc_core::get_instance()->head->add_jsfile(MIDGARDMVC_STATIC_URL . '/eu_urho_apartments/js/highlight.js');
    }


    /**
     * Retrieves an apartment by its name from the database
     *
     * @param string name of the apartment
     */
    private function get_apartment_by_name($name)
    {
        $q = new midgard_query_select
        (
            new midgard_query_storage('eu_urho_apartments_apartment')
        );
        $q->set_constraint
        (
            new midgard_query_constraint
            (
                new midgard_query_property('name'),
                '=',
                new midgard_query_value($name)
            )
        );

        $q->execute();

        if ($q->resultscount == 0)
        {
            $apartment = new eu_urho_apartments_apartment();
            $apartment->title = $name;
            $apartment->guid = 'placeholder';
            return $apartment;
        }

        $apartments = $q->list_objects();

        return $apartments[0];
    }

    /**
     * Reads the features from config file and returns their localized names
     *
     * @return array of features
     */
    public function retrieve_features()
    {
        $retval = array();

        $categories = $this->mvc->configuration->features;

        foreach ($categories as $category => $features)
        {
            $retval[$category]['css'] = $category;
            $retval[$category]['name'] = mb_convert_case($this->mvc->i18n->get($category), MB_CASE_TITLE, "UTF-8");
            foreach ($features as $feature)
            {
                $retval[$category]['list'][$feature]['css'] = $feature;
                $retval[$category]['list'][$feature]['name'] = $this->mvc->i18n->get($feature);
            }
        }

        return $retval;
    }
}
