<?php defined('_JEXEC') or die;

/**
 * File       feedmodifier.php
 * Created    8/5/13 10:37 AM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

jimport('joomla.plugin.plugin');

class plgSystemFeedmodifier extends JPlugin {

	function plgSystemFeedmodifier(&$subject, $config) {
		parent::__construct($subject, $config);

		$this->app    = JFactory::getApplication();
		$this->format = JRequest::getVar('format');
	}

	function onAfterRender() {

		if ($this->checkContext() === TRUE) {

			$author = htmlspecialchars($this->params->get('author'));
			$buffer = JResponse::getBody();

			$pattern     = '/<author>[^>]*<\/author>/i';
			$replacement = $author ? '<author>' . $author . '</author>' : '';
			$buffer      = preg_replace($pattern, $replacement, $buffer);
			preg_match($pattern, $buffer, $matches);

			JResponse::setBody($buffer);
		}

		return TRUE;
	}

	function checkContext() {

		if (!$this->app->isAdmin() && $this->format == "feed") {
			$item       = $this->app->getMenu()->getActive()->id;
			$exclusions = $this->params->get('exclusions');

			if (!is_array($exclusions)) {
				$exclusions = explode(' ', $exclusions);
			}

			if (!in_array($item, $exclusions)) {
				return TRUE;
			}
		}

		return FALSE;
	}
}