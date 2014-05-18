<?php
/**
 * @copyright (C) 2014 Albert Peschar
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');

$config = $this->config;

?>
<form action="<?php echo JRoute::_('index.php?option=com_evalor'); ?>" method="POST" name="adminForm" id="adminForm">
    <table class="wwk-form">
        <tr valign="top">
            <th scope="row"><label for="wwk-shop-id">ID de la tienda online</label></th>
            <td><input name="webwinkelkeur_wwk_shop_id" type="text" id="wwk-shop-id" value="<?php echo htmlspecialchars(@$config['wwk_shop_id'], ENT_QUOTES, 'UTF-8'); ?>" class="regular-text" /></td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="wwk-api-key">Clave API</label></th>
            <td><input name="webwinkelkeur_wwk_api_key" type="text" id="wwk-api-key" value="<?php echo htmlspecialchars(@$config['wwk_api_key'], ENT_QUOTES, 'UTF-8'); ?>" class="regular-text" />
            <p class="description">
            Estos datos los encontrará al ingresar en <a href="https://www.evalor.es/tienda/" target="_blank">eValor.es.</a><br />Haz click en "Colocar sello". Encontrará estos datos en la parte inferior de la página.
            </p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="webwinkelkeur-sidebar">Mostrar sidebar</label></th>
            <td>
                <label>
                    <input type="checkbox" id="webwinkelkeur-sidebar" name="webwinkelkeur_sidebar" value="1" <?php if(@$config['sidebar']) echo 'checked'; ?> />
                    Sí, añadir el sidebar de eValor a mi web.
                </label>
            </td>
        </tr> 
        <tr valign="top">
            <th scope="row">Posición sidebar</th>
            <td>
                <fieldset>
                    <label>
                        <input type="radio" name="webwinkelkeur_sidebar_position" value="left" <?php if(@$config['sidebar_position'] == 'left') echo 'checked'; ?> />
                        Izquierda                       
                    </label><br>
                    <label>
                        <input type="radio" name="webwinkelkeur_sidebar_position" value="right" <?php if(@$config['sidebar_position'] == 'right') echo 'checked'; ?> />
                        Derecha
                    </label>
                </fieldset>
            </td>
        </tr> 
        <tr valign="top">
            <th scope="row"><label for="webwinkelkeur-sidebar-top">Altura sidebar</label></th>
            <td><input name="webwinkelkeur_sidebar_top" type="text" id="webwinkelkeur-sidebar-top" value="<?php echo htmlspecialchars(@$config['sidebar_top'], ENT_QUOTES, 'UTF-8'); ?>" class="small-text" />
            <p class="description">
            Número de pixeles desde arriba.
            </p>
            </td>
        </tr>
        <?php if($this->virtuemart): ?>
        <tr valign="top">
            <th scope="row">Enviar invitaciones</th>
            <td>
                <fieldset>
                    <label>
                        <input type="radio" name="webwinkelkeur_invite" value="1" <?php if(@$config['invite'] == 1) echo 'checked'; ?> />
                        Sí, después de cada pedido.
                    </label><br>
                    <label>
                        <input type="radio" name="webwinkelkeur_invite" value="2" <?php if(@$config['invite'] == 2) echo 'checked'; ?> />
                        Sí, sólo con el primer pedido.
                    </label><br>
                    <label>
                        <input type="radio" name="webwinkelkeur_invite" value="0" <?php if(!@$config['invite']) echo 'checked'; ?> />
                        No, no enviar invitaciones.
                    </label>
                </fieldset>
                <p class="description">Esta función solo está disponible para socios PLUS.</p>
            </td>
        </tr> 
        <tr valign="top">
            <th scope="row"><label for="webwinkelkeur-invite-delay">Plazo para la invitación</label></th>
            <td><input name="webwinkelkeur_invite_delay" type="text" id="webwinkelkeur-invite-delay" value="<?php echo htmlspecialchars(@$config['invite_delay'], ENT_QUOTES, 'UTF-8'); ?>" class="small-text" />
            <p class="description">
            La invitación se envía una vez hayan pasado el número de días indicados después de enviar el pedido. 
            </p>
            </td>
        </tr>
        <?php endif; ?>
        <tr valign="top">
            <th scope="row"><label for="webwinkelkeur-tooltip">Mostrar logo desplegable</label></th>
            <td>
                <label>
                    <input type="checkbox" id="webwinkelkeur-tooltip" name="webwinkelkeur_tooltip" value="1" <?php if(@$config['tooltip']) echo 'checked'; ?> />
                    Sí, añadir el logo desplegable eValor a mi sitio web.
                </label>
            </td>
        </tr> 
        <tr valign="top">
            <th scope="row"><label for="webwinkelkeur-javascript">Integración JavaScript</label></th>
            <td>
                <label>
                    <input type="checkbox" id="webwinkelkeur-javascript" name="webwinkelkeur_javascript" value="1" <?php if(@$config['javascript']) echo 'checked'; ?> />
                    Sí, añadir el JavaScript de eValor a mi sitio web. 
                </label>
            </td>
        </tr> 
    </table>
    <div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
    </div>
</form>
