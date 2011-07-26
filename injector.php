<?php
/**
 * @package eu_urho_apartments
 * @author Ferenc Szekely (ferenc.szekely@urho.eu)
 * @copyright Urho Ltd (info@urho.eu)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * Injector
 *
 * @package eu_urho_apartments
 */
class eu_urho_apartments_injector
{
    var $component = 'eu_urho_apartments';
    var $request = null;
    var $midgardmvc = null;

    /**
     * Some template hack
     */
    public function inject_template(midgardmvc_core_request $request)
    {
        $this->request = $request;
        $this->midgardmvc = midgardmvc_core::get_instance();

        $this->request->add_component_to_chain($this->midgardmvc->component->get($this->component), true);

        $this->midgardmvc->i18n->set_translation_domain($this->component);
        $this->midgardmvc->i18n->set_language($this->midgardmvc->configuration->default_language, false);

        $route = $request->get_route();

        $route->template_aliases['loginbox'] = 'eua-loginbox';

        if ($this->midgardmvc->authentication->is_user())
        {
            $request->set_data_item('eua_user', $this->midgardmvc->authentication->get_person());
            $route->template_aliases['loginbox'] = 'eua-userbox';
        }
    }
}
?>
