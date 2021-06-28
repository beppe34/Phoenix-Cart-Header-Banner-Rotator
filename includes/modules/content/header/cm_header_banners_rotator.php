<?php

/*
  $Id: cm_header_banners.php
  $Loc: catalog/includes/modules/content/header/

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  banner modules 2.1
  by @raiwa 
  info@oscaddons.com
  www.oscaddons.com

  Copyright (c) 2020 Rainer Schmied
*/

  class cm_header_banners_rotator extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_HEADER_BANNERS_ROTATOR_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      $content_width = MODULE_CONTENT_HEADER_BANNERS_ROTATOR_CONTENT_WIDTH;
          
      if (tep_not_null(MODULE_CONTENT_HEADER_BANNERS_ROTATOR_GROUPS)) {
          $rotatorgroup = MODULE_CONTENT_HEADER_BANNERS_ROTATOR_GROUPS;
          $sql=<<<sql
    SELECT a.*, ai.*  
    FROM advert a
    LEFT JOIN advert_info ai ON a.advert_id = ai.advert_id
    WHERE a.status = '1'  
    and ai.languages_id={$_SESSION['languages_id']} 
    AND a.advert_group IN ($rotatorgroup) 
    ORDER BY a.sort_order;                  
sql;                  
        $banner_query = tep_db_query($sql);

        if (tep_db_num_rows($banner_query) > 0) {
      
          $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
          include 'includes/modules/content/cm_template.php';
        }
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_HEADER_BANNERS_ROTATOR_VERSION_INSTALLED' => [
          'title' => 'Current Version',
          'value' => '2.1',
          'desc' => 'Version info. It is read only',
          'set_func' => 'cm_header_banners_rotator::readonly(',
        ],
        'MODULE_CONTENT_HEADER_BANNERS_ROTATOR_STATUS' => [
          'title' => 'Enable Header Banners Rotator',
          'value' => 'True',
          'desc' => 'Do you want to enable the Header Banner rotator content module?',
          'set_func' => "tep_cfg_select_option(['True', 'False'], ",
        ],
        'MODULE_CONTENT_HEADER_BANNERS_ROTATOR_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "tep_cfg_select_option(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_HEADER_BANNERS_ROTATOR_BANNER_WIDTH' => [
          'title' => 'Banner Width',
          'value' => '6',
          'desc' => 'What width container should each banner be shown in?',
          'set_func' => "tep_cfg_select_option(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_HEADER_BANNERS_ROTATOR_GROUPS' => [
          'title' => 'Banner Groups',
          'value' => '',
          'desc' => 'Check the Banner Groups to show in this module.',
          'use_func' => 'cm_header_banners_rotator::show_modules',
          'set_func' => 'cm_header_banners_rotator::edit_modules(',
        ],
        'MODULE_CONTENT_HEADER_BANNERS_ROTATOR_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

    public static function readonly($value) {
      return $value;
    }

    public static function show_modules($text) {
      return nl2br(implode("\n", explode(',', str_replace('\'', '', $text))));
    }

    public static function edit_modules($values, $key) {
      global $PHP_SELF;

      $group_query = tep_db_query("SELECT DISTINCT advert_group FROM advert WHERE status = '1'");

      $values_array = explode(',', $values);

      $output = '';
      while ($group = tep_db_fetch_array($group_query)) {
        $output .= tep_draw_checkbox_field($key . 'st_group_module[]', '\'' . $group['advert_group'] . '\'', in_array('\'' . $group['advert_group'] . '\'', $values_array), null, True) . '&nbsp;' . tep_output_string($group['advert_group']) . '<br />';
      }

      if (!empty($output)) {
        $output = '<br>' . substr($output, 0, -6);
      }

      $output .= tep_draw_hidden_field('configuration[' . $key . ']', '', 'id="' . $key . 'htrn_group_modules"');

      $output .= '<script>
                  function ' . $key . 'htrn_group_update_cfg_value() {
                    var ' . $key . 'htrn_group_selected_modules = \'\';

                    if ($(\'input[name="' . $key . 'st_group_module[]"]\').length > 0) {
                      $(\'input[name="' . $key . 'st_group_module[]"]:checked\').each(function() {
                        ' . $key . 'htrn_group_selected_modules += $(this).attr(\'value\') + \',\';
                      });

                      if (' . $key . 'htrn_group_selected_modules.length > 0) {
                        ' . $key . 'htrn_group_selected_modules = ' . $key . 'htrn_group_selected_modules.substring(0, ' . $key . 'htrn_group_selected_modules.length - 1);
                      }
                    }

                    $(\'#' . $key . 'htrn_group_modules\').val(' . $key . 'htrn_group_selected_modules);
                  }

                  $(function() {
                    ' . $key . 'htrn_group_update_cfg_value();

                    if ($(\'input[name="' . $key . 'st_group_module[]"]\').length > 0) {
                      $(\'input[name="' . $key . 'st_group_module[]"]\').change(function() {
                        ' . $key . 'htrn_group_update_cfg_value();
                      });
                    }
                  });
                  </script>';

      return $output;
    }

  }
