<?php
/**
 * @package eu_urho_apartments
 * @author Ferenc Szekely (ferenc.szekely@urho.eu)
 * @copyright Urho Ltd (info@urho.eu)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * Index controller
 */
class eu_urho_apartments_controllers_index
{
    public function __construct(midgardmvc_core_request $request)
    {
        $this->request = $request;
        $this->mvc = midgardmvc_core::get_instance();
    }

    /**
     * Provides the index page
     *
     * @param array HTTP request arguments
     */
    public function get_index(array $args)
    {
        $this->data['apartments'] = $this->get_apartments();
    }

    /**
     * Get all available apartments
     *
     * @param string name of the apartment
     * @return collection
     */
    public function get_apartments()
    {
        $collection = new midgardmvc_ui_create_container();

        $placeholder = new eu_urho_apartments_apartment();
        $placeholder->url = '';
        $collection->set_placeholder($placeholder);

        $q = new midgard_query_select
        (
            new midgard_query_storage('eu_urho_apartments_apartment')
        );

        $q->execute();

        array_walk
        (
            $q->list_objects(),
            function ($apartment) use ($collection)
            {
                $apartment->url = $this->mvc->dispatcher->generate_url('apartment', array('apartment' => $apartment->name), 'eu_urho_apartments');
                $collection->attach($apartment);
            }
        );

        return $collection;
    }
}

?>