<?php
class eu_urho_apartments_controllers_apartment
{
    public function __construct(midgardmvc_core_request $request)
    {
        $this->request = $request;
    }

    /**
     * Retrieves an apartment from the database
     *
     * @param array HTTP request arguments
     */
    public function get_apartment(array $args)
    {
        $this->data['apartment'] = $this->get_apartment_by_title($this->request->get_node()->get_object()->title);
        $this->data['apartment']->rdfmapper = new midgardmvc_ui_create_rdfmapper($this->data['apartment']);
        midgardmvc_core::get_instance()->head->set_title($this->data['apartment']->title);

        midgardmvc_core::get_instance()->head->enable_jquery();
        midgardmvc_core::get_instance()->head->add_jsfile(MIDGARDMVC_STATIC_URL . '/eu_urho_apartments/js/menu.js');
        midgardmvc_core::get_instance()->head->add_jsfile(MIDGARDMVC_STATIC_URL . '/eu_urho_apartments/js/downloadmenu.js');
        midgardmvc_core::get_instance()->head->add_jsfile(MIDGARDMVC_STATIC_URL . '/eu_urho_apartments/js/highlight.js');

    }


    /**
     * Retrieves an apartment by its title from the database
     *
     * @param string title of the apartment
     */
    private function get_apartment_by_title($title = null)
    {
        if (! $title)
        {
            return null;
        }

        $q = new midgard_query_select
        (
            new midgard_query_storage('eu_urho_apartments_apartment')
        );
        $q->set_constraint
        (
            new midgard_query_constraint
            (
                new midgard_query_property('title'),
                '=',
                new midgard_query_value($title)
            )
        );

        $q->execute();

        if ($q->resultscount == 0)
        {
            $apartment = new eu_urho_apartments_apartment();
            $apartment->title = $title;
            $apartment->guid = 'placeholder';
            return $apartment;
        }

        $apartments = $q->list_objects();

        return $apartments[0];
    }
}
