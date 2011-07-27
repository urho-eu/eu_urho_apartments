<?php
/**
 * @package eu_urho_apartments
 * @author Ferenc Szekely (ferenc.szekely@urho.eu)
 * @copyright Urho Ltd (info@urho.eu)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * Utils
 *
 * @package eu_urho_apartments
 */
class eu_urho_apartments_utils
{
    /**
     * Setting language
     *
     */
    public function set_language()
    {
        $this->component = 'eu_urho_apartments';
        $this->mvc = midgardmvc_core::get_instance();
        $this->request = $this->mvc->dispatcher->get_request();

        $this->mvc->i18n->set_translation_domain($this->component);
        $this->mvc->i18n->set_language($this->mvc->configuration->default_language, false);

        if (isset($_GET['lang']))
        {
            try
            {
                $this->mvc->i18n->set_language($_GET['lang'], false);
            }
            catch (Exception $e)
            {
                // Check your locale configuration!
                // A requested language should be supported by the host server.
                // Revert to default
                $this->mvc->i18n->set_language($this->mvc->configuration->default_language, false);
            }
        }
    }
}
?>
