<?php
/*
*  @author iLet Developer Fabio Zaffani <fabiozaffani@gmail.com>
*  @version  Release: $Revision: 1.0 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class talkalotModule extends Module
{

	protected static $cookie;
   	private $_html;

	public function __construct()
	{
		$this->name = 'talkalotmodule';
		$this->tab = 'front_office_features';
		$this->version = 1.0;
		$this->author = 'iLet Develop Team';
		$this->need_instance = 0;
				
		parent::__construct();
		
		$this->displayName = $this->l('Facebook Comments Talk a Lot Module');
		$this->description = $this->l('Add the Facebook Comment system to your Product Page');
	}

	public function install()
	{
	 	if (
            !parent::install() OR
			!$this->registerHook('top') OR
			!$this->registerHook('header') OR
			!$this->registerHook('productFooter') OR
			!$this->registerHook('footer') OR
			!Configuration::updateValue('TALKALOT_ALREADYFACE', 0) OR
			!Configuration::updateValue('TALKALOT_COMMENTS_POSTS', 10) OR
			!Configuration::updateValue('TALKALOT_COMMENTS_WIDTH', 500) OR
			!Configuration::updateValue('TALKALOT_APP_ID', '') OR
			!Configuration::updateValue('TALKALOT_ADMIN_USER_ID', '') OR
			!Configuration::updateValue('TALKALOT_ADMIN_CONTACT_EMAIL', 'something@example.com') OR
			!Configuration::updateValue('TALKALOT_APP_LANG', 'en_US') OR
			!Configuration::updateValue('SHARE_COM', 1) OR
            !Configuration::updateValue('TALKALOT_COMMENTS_COLOR', 'light')
		)
	 		return false;
	 	return true;
	}
	
	public function uninstall()
	{
	 	if (
            !parent::uninstall() OR
			!$this->unregisterHook('top') OR
			!$this->unregisterHook('header') OR
			!$this->unregisterHook('productFooter') OR
			!$this->unregisterHook('footer') OR
			!Configuration::deleteByName('TALKALOT_ALREADYFACE') OR
			!Configuration::deleteByName('TALKALOT_COMMENTS_POSTS') OR
			!Configuration::deleteByName('TALKALOT_COMMENTS_WIDTH') OR
			!Configuration::deleteByName('TALKALOT_APP_ID') OR
			!Configuration::deleteByName('TALKALOT_ADMIN_USER_ID') OR
			!Configuration::deleteByName('TALKALOT_ADMIN_CONTACT_EMAIL') OR
			!Configuration::deleteByName('TALKALOT_APP_LANG') OR
			!Configuration::deleteByName('SHARE_COM') OR
			!Configuration::deleteByName('TALKALOT_COMMENTS_COLOR')
		)
	 		return false;
	 	return true;
	}

	public function getContent()
	{
		$this->_html = '';
		if (Tools::isSubmit('submitFace'))
		{
			if (Tools::getValue('commentsPosts') == '' OR Tools::getValue('commentsPosts') == 0)
				$this->_html .= $this->displayError('You have to set 1 or higher for number of comments to show');
			if (Tools::getValue('commentsWidth') == '')
				$this->_html .= $this->displayError('You have to set a value for Facebook Comments Width');
			else
			{
				Configuration::updateValue('TALKALOT_COMMENTS_COLOR', Tools::getValue('colorScheme'));	
                Configuration::updateValue('TALKALOT_ALREADYFACE', Tools::getValue('alreadyFace'));
				Configuration::updateValue('TALKALOT_APP_LANG', Tools::getValue('language'));
				Configuration::updateValue('TALKALOT_COMMENTS_POSTS', Tools::getValue('commentsPosts'));
				Configuration::updateValue('TALKALOT_COMMENTS_WIDTH', Tools::getValue('commentsWidth'));
				Configuration::updateValue('TALKALOT_APP_ID', Tools::getValue('appID'));
				Configuration::updateValue('TALKALOT_ADMIN_USER_ID', Tools::getValue('adminID'));
				Configuration::updateValue('TALKALOT_ADMIN_CONTACT_EMAIL', Tools::getValue('adminContact'));
				$this->_html .= $this->displayConfirmation($this->l('Settings updated successfully'));
			}
		}

		$this->_html .= '
		
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">';
		
		// FACEBOOK GENERAL CONFIG
                $this->_html .='
            <fieldset>
            	<div style="width:400px;margin:10px auto;color:#333333;font-weight:bold;font-size:12px;font-style:italic;text-align:center">Before we start, make sure you already have an Application Created on Facebook. If you dont know how to do it, here is <a href="http://www.pazzanitech.com.br/how-to-create-a-facebook-app" target="_blank" style="color:#3B5998;">How to Create a Facebook APP</a>.</div>
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Facebook General Settings').'</legend>
				<div class="margin-form">
					<label>'.$this->l('Have other Facebook Module?').'</label>
					<input type="radio" name="alreadyFace" id="alreadyFace" value="1" '.(Configuration::get('TALKALOT_ALREADYFACE') ? 'checked="checked" ' : '').'/>
					<label class="t" for="alreadyFace"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="alreadyFace" id="notAlreadyFace" value="0" '.(!Configuration::get('TALKALOT_ALREADYFACE') ? 'checked="checked" ' : '').'/>
					<label class="t" for="notAlreadyFace"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label><br/>
					<small style="padding-left:90px;display:block;margin-top:6px">Enable <b>ONLY<b/> if you already have another Module using Facebook Integration.</small>
				</div>
				<br/>
				<div class="margin-form">
					<label>'.$this->l('Facebook APP ID').'</label>
					<input type="text" name="appID" id="appID" value="'.(Configuration::get('TALKALOT_APP_ID')).'" /><br/>
					<small style="padding-left:80px;display:block;margin-top:6px">You MUST provide your own APP ID, otherwise the module WILL NOT WORK. <a href="http://www.pazzanitech.com.br/how-to-create-a-facebook-app" target="_blank" style="color:#3B5998;">How to Create a Facebook APP</a></small>
				</div>
				<br/>
				<div class="margin-form">
					<label>'.$this->l('Facebook Admin User ID').'</label>
					<input type="text" name="adminID" id="adminID" value="'.(Configuration::get('TALKALOT_ADMIN_USER_ID')).'" /><br/>
					<small style="padding-left:80px;display:block;margin-top:6px">Mandatory if you want to moderate the comments. Heres a link how to <a href="http://www.facebook.com/topic.php?uid=27817827944&topic=13276" target="_blank" style="color:#3B5998;">get your Facebook User ID</a></small>
				</div>
				<br/>
				<div class="margin-form">
					<label>'.$this->l('Facebook APP Language').'</label>
					<input type="text" name="language" id="language" value="'.(Configuration::get('TALKALOT_APP_LANG')).'" /><br/>
					<small style="padding-left:90px;display:block;margin-top:6px">Choose your language. Examples: es_LA, pt_BR, en_US</small>				
				</div>
				<br/>
				<div class="margin-form">
					<label>'.$this->l('Admin E-mail').'</label>
					<input type="text" name="adminContact" id="adminContact" value="'.(Configuration::get('TALKALOT_ADMIN_CONTACT_EMAIL')).'" /><br/>
					<small style="padding-left:90px;display:block;margin-top:6px">The same e-mail you used when you setted your Application on Facebook</small>				
				</div>
				<br/>
				<center><input type="submit" name="submitFace" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset><br/>';
		
		// COMMENTS
		$this->_html .='
		<fieldset>
			<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Comments Settings').'</legend>
			
             <div class="margin-form">
                   <label>'.$this->l('Color Scheme').'</label>
                   <input type="radio" name="colorScheme" id="colorSchemeLight" value="light" '.(Configuration::get('TALKALOT_COMMENTS_COLOR') == 'light' ? 'checked="checked" ' : '').'/>
                   <label class="t" for="colorSchemeLight"> Light </label>
                   <input type="radio" name="colorScheme" id="colorSchemeDark" value="dark" '.(Configuration::get('TALKALOT_COMMENTS_COLOR') == 'dark' ? 'checked="checked" ' : '').'/>
                   <label class="t" for="colorSchemeDark"> Dark </label><br/>
                   <small style="padding-left:90px;display:block;margin-top:6px">Choose the color scheme for your comment box. Default is light</small>
			</div>
            <div class="margin-form">
				<label>'.$this->l('Number of Posts').'</label>
				<input type="text" name="commentsPosts" id="commentsPosts" value="'.(Configuration::get('TALKALOT_COMMENTS_POSTS')).'" />
			</div>
			<div class="margin-form">
				<label>'.$this->l('Comments Width').'</label>
				<input type="text" name="commentsWidth" id="commentsWidth" value="'.(Configuration::get('TALKALOT_COMMENTS_WIDTH')).'" />
			</div>
			<center><input type="submit" name="submitFace" value="'.$this->l('Save').'" class="button" /></center>
		</fieldset><br/>';
		return $this->_html;
	}

	public function hookFooter($params)
	{
		global $smarty;
		$likeExists = Module::isInstalled('likealotmodule');
		$caroExists = Module::isInstalled('cleancarroussel');
          	
        if($likeExists && Configuration::get('LIKE_SHARE'))
			return;

      	if($caroExists && Configuration::get('SHARE_CAR'))
      		return;

        $thanks = '<span style="font-size:11px;font-color:#999999;font-style:italic;margin-top:11px;float:left">Module from the creators of <a href="http://www.guitarpro6.com.br/" target="_blank">Guitar Pro 6</a> :: More at <a href="http://www.pazzanitech.com.br/prestashop-modules" target="_blank">Prestashop Modules</a></span>';
        $smarty->assign(array('clear' => $thanks));
        return $this->display(__FILE__, 'talkalot-footer.tpl');
	}
	
	public function hookHeader($params)
	{
		global $smarty;
		
		Tools::addCSS(($this->_path).'talkalot.css', 'all');

		$smarty->assign(array(
			'app_id' => Configuration::get('TALKALOT_APP_ID'),
			'admin_id' => Configuration::get('TALKALOT_ADMIN_USER_ID'),
			'contact_email' => Configuration::get('TALKALOT_ADMIN_CONTACT_EMAIL'),
			));
		return $this->display(__FILE__, 'talkalot-header.tpl');		
	}
	
	public function hookTop($params)
	{
		$alreadyFace = Configuration::get('TALKALOT_ALREADYFACE');
		
		if ($alreadyFace)
			return;
                global $smarty;
                $smarty->assign(array(
                        'app_id' => Configuration::get('TALKALOT_APP_ID'),
                        'lang' => Configuration::get('LIKE_FACEBOOK_APP_LANG')
                        ));
                return $this->display(__FILE__, 'talkalot-top.tpl');
	}

	public function hookProductFooter($params)
	{
		global $smarty;
		
		$smarty->assign(array(
			'posts' => Configuration::get('TALKALOT_COMMENTS_POSTS'),
			'width' => Configuration::get('TALKALOT_COMMENTS_WIDTH'),
            'color'  => Configuration::get('TALKALOT_COMMENTS_COLOR'),
            'text' => Configuration::get('TALKALOT_COMMENTS_COMMENTS_TEXT')	
		));
		return $this->display(__FILE__, 'talkalot-comments.tpl');
	}
}
?>