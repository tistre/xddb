<?php

header('Content-Type: application/xhtml+xml; charset=UTF-8');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-type" content="application/xhtml+xml; charset=UTF-8" />
    <meta charset="UTF-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="<?=$tpl[ 'topicbank_static_base_url' ]?>bootstrap/assets/ico/favicon.ico" />

    <title>
      <?=htmlspecialchars($tpl[ 'topic' ][ 'id' ])?> | 
      <?=htmlspecialchars($tpl[ 'topicmap' ][ 'display_name' ])?>
    </title>

    <!-- Bootstrap core CSS -->
    <link href="<?=$tpl[ 'topicbank_static_base_url' ]?>bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="<?=$tpl[ 'topicbank_static_base_url' ]?>topicbank.css" rel="stylesheet" />

  </head>

  <body>

    <div class="container">
      <div class="header">
        <ul class="nav nav-pills pull-right">
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              <span class="glyphicon glyphicon-cog"></span>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="<?=$tpl[ 'topicbank_base_url' ]?>edit_new_topic"><span class="glyphicon glyphicon-plus"></span> Create a new topic</a></li>
              <li><a href="#">Logged in as …</a></li>
              <li><a href="#">Log out</a></li>
            </ul>
          </li>
        </ul>
      <form class="form-inline pull-right" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search" />
        </div>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
      </form>
        <h3 class="text-muted"><?=htmlspecialchars($tpl[ 'topicmap' ][ 'display_name' ])?></h3>
      </div>

      <?php if (! empty($tpl[ 'error_html' ])) { ?>
      <div class="alert alert-danger"><?=$tpl[ 'error_html' ]?></div>
      <?php } ?>

      <form id="topicbank_form_edit" method="post" action="">

      <div class="well well-lg">

          <div style="padding-bottom: 15px; border-bottom: 1px solid #CCCCCC;">

            <!-- Types -->

            <div class="pull-right">
              <table>
          
            <?php
          
            foreach ($tpl[ 'topic' ][ 'types' ] as $type_id)
            {
                ?>
                <tr>
                  <td>
                    <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="topic_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                      <span data-topicbank_element="name"><?=htmlspecialchars($tpl[ 'topic_names' ][ $type_id ])?></span>:
                      <span class="caret"></span>
                    </button>
                    <input type="hidden" name="types[]" value="<?=htmlspecialchars($type_id)?>" data-topicbank_element="id" />
                  </td>
                  <td>
                    <button class="btn btn-link" type="button" data-topicbank_event="remove">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                  </td>
                </tr>
                <?php
            }
            
            ?>

                <tr data-topicbank_template="new_type" class="hidden">
                  <td>
                    <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="topic_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                      <span data-topicbank_element="name">[Choose type]</span>:
                      <span class="caret"></span>
                    </button>
                    <input type="hidden" name="types[]" value="" data-topicbank_element="id" />
                  </td>
                  <td>
                    <button class="btn btn-link" type="button" data-topicbank_event="remove">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                  </td>
                </tr>
                
                <tr>
                  <td>
                    <button data-topicbank_event="new_type" class="btn btn-link" type="button">
                      <span class="glyphicon glyphicon-plus"></span>
                      Add a type
                    </button>
                  </td>
                </tr>
            
              </table>
            </div>
          
            <!-- Unscoped base names -->

            <div>
            
            <?php 
          
            if (count($tpl[ 'topic' ][ 'unscoped_basenames' ]) === 0) 
                $tpl[ 'topic' ][ 'unscoped_basenames' ][ ] = [ 'value' => '' ]; 
            
            foreach ($tpl[ 'topic' ][ 'unscoped_basenames' ] as $name) 
            { 
                ?>
          
              <input type="text" name="unscoped_basenames[]" value="<?=htmlspecialchars($name[ 'value' ])?>" style="font-weight: 500; font-size: 36px;" />
              <br />
          
                <?php 
            } 
        
            ?>
            
            </div>

          </div>
          
          <!-- Additional names -->
        
          <div>
          
            <h4>Additional names</h4>
            
            <table>
          
            <?php $i = -1; foreach ($tpl[ 'topic' ][ 'other_names' ] as $i => $name) { ?>

              <tr>
                <td>
                  <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="name_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                    <span data-topicbank_element="name"><?=htmlspecialchars($tpl[ 'topic_names' ][ $name[ 'type' ] ])?></span>:
                    <span class="caret"></span>
                  </button>
                  <input type="hidden" name="other_names[<?=$i?>][type]" value="<?=htmlspecialchars($name[ 'type' ])?>" data-topicbank_element="id" />
                </td>
                <td><input type="text" name="other_names[<?=$i?>][value]" value="<?=htmlspecialchars($name[ 'value' ])?>" /></td>
                <td>
                  <?php if (count($name[ 'scope' ]) === 0) $name[ 'scope' ][ ] = ''; foreach ($name[ 'scope' ] as $scope) { ?>
                  <input type="text" name="other_names[<?=$i?>][scope][]" value="<?=htmlspecialchars($scope)?>" />
                  <?php } ?>
                </td>
                <td>
                  <button class="btn btn-link" type="button" data-topicbank_event="remove">
                    <span class="glyphicon glyphicon-remove"></span>
                  </button>
                </td>
              </tr>
          
            <?php } $i++; ?>

              <tr data-topicbank_template="new_name" class="hidden" data-topicbank_counter_value="<?=$i?>" data-topicbank_counter_name="TOPICBANK_COUNTER1">
                <td>
                  <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="name_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                    <span data-topicbank_element="name">[Choose type]</span>:
                    <span class="caret"></span>
                  </button>
                  <input type="hidden" name="other_names[TOPICBANK_COUNTER1][type]" value="" data-topicbank_element="id" />
                </td>
                <td><input type="text" name="other_names[TOPICBANK_COUNTER1][value]" value="" /></td>
                <td><input type="text" name="other_names[TOPICBANK_COUNTER1][scope][]" value="" /></td>
                <td>
                  <button class="btn btn-link" type="button" data-topicbank_event="remove">
                    <span class="glyphicon glyphicon-remove"></span>
                  </button>
                </td>
              </tr>

              <tr>
                <td>
                  <button data-topicbank_event="new_name" class="btn btn-link" type="button">
                    <span class="glyphicon glyphicon-plus"></span>
                    Add a name
                  </button>
                </td>
              </tr>
          
            </table>

          </div>
          
          <!-- Subject identifiers -->
        
          <div>
          
            <h4>Identifier URLs</h4>
            
            <table>
          
            <?php
          
            foreach ($tpl[ 'topic' ][ 'subject_identifiers' ] as $url)
            {
                ?>
                <tr>
                  <td>
                    <input type="text" name="subject_identifiers[]" value="<?=htmlspecialchars($url)?>" style="width: 400px;" />
                  </td>
                  <td>
                    <button class="btn btn-link" type="button" data-topicbank_event="remove">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                  </td>
                </tr>
                <?php
            }
            
            ?>
            
              <tr data-topicbank_template="new_subject_identifier" class="hidden">
                <td>
                  <input type="text" name="subject_identifiers[]" value="" style="width: 400px;" />
                </td>
                <td>
                  <button class="btn btn-link" type="button" data-topicbank_event="remove">
                    <span class="glyphicon glyphicon-remove"></span>
                  </button>
                </td>
              </tr>
              
              <tr>
                <td>
                  <button data-topicbank_event="new_subject_identifier" class="btn btn-link" type="button">
                    <span class="glyphicon glyphicon-plus"></span>
                    Add an identifier URL
                  </button>
                </td>
              </tr>
              
            </table>

          </div>
          
          <!-- Subject locators -->
        
          <div>
          
            <h4>Resource URLs</h4>
            
            <table>
          
            <?php
          
            foreach ($tpl[ 'topic' ][ 'subject_locators' ] as $url)
            {
                ?>
                <tr>
                  <td>
                    <input type="text" name="subject_locators[]" value="<?=htmlspecialchars($url)?>" style="width: 400px;" />
                  </td>
                  <td>
                    <button class="btn btn-link" type="button" data-topicbank_event="remove">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                  </td>
                </tr>
                <?php
            }
            
            ?>
            
              <tr data-topicbank_template="new_subject_locator" class="hidden">
                <td>
                  <input type="text" name="subject_locators[]" value="" style="width: 400px;" />
                </td>
                <td>
                  <button class="btn btn-link" type="button" data-topicbank_event="remove">
                    <span class="glyphicon glyphicon-remove"></span>
                  </button>
                </td>
              </tr>
                
              <tr>
                <td>
                  <button data-topicbank_event="new_subject_locator" class="btn btn-link" type="button">
                    <span class="glyphicon glyphicon-plus"></span>
                    Add a resource URL
                  </button>
                </td>
              </tr>

            </table>

          </div>

          <!-- Occurrences -->
        
          <div>
          
            <h4>Properties</h4>
            
            <table>
          
            <?php $i = -1; foreach ($tpl[ 'topic' ][ 'occurrences' ] as $i => $occurrence) { ?>

              <tr>
                <td>
                  <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="occurrence_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                    <span data-topicbank_element="name"><?=htmlspecialchars($tpl[ 'topic_names' ][ $occurrence[ 'type' ] ])?></span>:
                    <span class="caret"></span>
                  </button>
                  <input type="hidden" name="occurrences[<?=$i?>][type]" value="<?=htmlspecialchars($occurrence[ 'type' ])?>" data-topicbank_element="id" />
                </td>
                <td>
                  <input type="text" name="occurrences[<?=$i?>][value]" value="<?=htmlspecialchars($occurrence[ 'value' ])?>" />
                  <br />
                  <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="occurrence_datatype" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                    <span data-topicbank_element="name"><?=htmlspecialchars($tpl[ 'topic_names' ][ $occurrence[ 'datatype' ] ])?></span>:
                    <span class="caret"></span>
                  </button>
                  <input type="hidden" name="occurrences[<?=$i?>][datatype]" value="<?=htmlspecialchars($occurrence[ 'datatype' ])?>" data-topicbank_element="id" />
                </td>
                <td>
                  <?php if (count($occurrence[ 'scope' ]) === 0) $occurrence[ 'scope' ][ ] = ''; foreach ($occurrence[ 'scope' ] as $scope) { ?>
                  <input type="text" name="occurrences[<?=$i?>][scope][]" value="<?=htmlspecialchars($scope)?>" />
                  <?php } ?>
                </td>
                <td>
                  <button class="btn btn-link" type="button" data-topicbank_event="remove">
                    <span class="glyphicon glyphicon-remove"></span>
                  </button>
                </td>
              </tr>
          
            <?php } $i++; ?>

              <tr data-topicbank_template="new_occurrence" class="hidden" data-topicbank_counter_value="<?=$i?>" data-topicbank_counter_name="TOPICBANK_COUNTER1">
                <td>
                  <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="occurrence_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                    <span data-topicbank_element="name">[Choose property]</span>:
                    <span class="caret"></span>
                  </button>
                  <input type="hidden" name="occurrences[TOPICBANK_COUNTER1][type]" value="" data-topicbank_element="id" />
                </td>
                <td>
                  <input type="text" name="occurrences[TOPICBANK_COUNTER1][value]" value="" />
                  <br />
                  <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="occurrence_datatype" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                    <span data-topicbank_element="name">[Choose datatype]</span>:
                    <span class="caret"></span>
                  </button>
                  <input type="hidden" name="occurrences[TOPICBANK_COUNTER1][datatype]" value="" data-topicbank_element="id" />
                </td>
                <td><input type="text" name="occurrences[TOPICBANK_COUNTER1][scope][]" value="" /></td>
                <td>
                  <button class="btn btn-link" type="button" data-topicbank_event="remove">
                    <span class="glyphicon glyphicon-remove"></span>
                  </button>
                </td>
              </tr>

              <tr>
                <td>
                  <button data-topicbank_event="new_occurrence" class="btn btn-link" type="button">
                    <span class="glyphicon glyphicon-plus"></span>
                    Add a property
                  </button>
                </td>
              </tr>
          
            </table>

          </div>
                    
          <!-- Save button -->
          
          <div>
            <p class="pull-right">
              <a href="<?=htmlspecialchars($tpl[ 'cancel_url' ])?>" class="btn btn-link">Cancel</a>
              <button type="submit" class="btn btn-primary">Save</button>
            </p>
          </div>
          
      </div>

      <!-- Associations -->
    
      <div>
      
        <h4>Associations</h4>
        
        <table>
      
        <?php $i = -1; foreach ($tpl[ 'associations' ] as $i => $association) { ?>

          <tr>
            <td>
              <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="association_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                <span data-topicbank_element="name"><?=htmlspecialchars($tpl[ 'topic_names' ][ $association[ 'type' ] ])?></span>:
                <span class="caret"></span>
              </button>
              <input type="hidden" name="associations[<?=$i?>][type]" value="<?=htmlspecialchars($association[ 'type' ])?>" data-topicbank_element="id" />
              
              <input type="hidden" name="associations[<?=$i?>][id]" value="<?=htmlspecialchars($association[ 'id' ])?>" />
              <input type="hidden" name="associations[<?=$i?>][delete]" value="0" />
            </td>
            <td>
            
              <table>

                <?php foreach ($association[ 'roles' ] as $j => $role) { ?>
                <tr>
                  <td>
                    <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="role_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                      <span data-topicbank_element="name"><?=htmlspecialchars($tpl[ 'topic_names' ][ $role[ 'type' ] ])?></span>:
                      <span class="caret"></span>
                    </button>
                    <input type="hidden" name="associations[<?=$i?>][roles][<?=$j?>][type]" value="<?=htmlspecialchars($role[ 'type' ])?>" data-topicbank_element="id" />
                  </td>
                  <td>
                    <?php if ($role[ 'this_topic' ]) { ?>
                    (This topic)
                    <?php } else { ?>
                    <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="role_player" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                      <span data-topicbank_element="name"><?=htmlspecialchars($tpl[ 'topic_names' ][ $role[ 'player' ] ])?></span>:
                      <span class="caret"></span>
                    </button>
                    <?php } ?>
                    <input type="hidden" name="associations[<?=$i?>][roles][<?=$j?>][player]" value="<?=htmlspecialchars($role[ 'player' ])?>" data-topicbank_element="id" />
                  </td>
                  <td>
                    <?php if (! $role[ 'this_topic' ]) { ?>
                    <button class="btn btn-link" type="button" data-topicbank_event="remove">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                    <?php } ?>
                  </td>
                </tr>
                <?php } $j++; ?>

                <tr data-topicbank_template="new_role" class="hidden" data-topicbank_counter_value="<?=$j?>" data-topicbank_counter_name="TOPICBANK_COUNTER2">
                  <td>
                    <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="role_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                      <span data-topicbank_element="name">[Choose role type]</span>:
                      <span class="caret"></span>
                    </button>
                    <input type="hidden" name="associations[<?=$i?>][roles][TOPICBANK_COUNTER2][type]" value="" data-topicbank_element="id" />
                  </td>
                  <td>
                    <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="role_player" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                      <span data-topicbank_element="name">[Choose role player]</span>:
                      <span class="caret"></span>
                    </button>
                    <input type="hidden" name="associations[<?=$i?>][roles][TOPICBANK_COUNTER2][player]" value="" data-topicbank_element="id" />
                  </td>
                  <td>
                    <button class="btn btn-link" type="button" data-topicbank_event="remove">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                  </td>
                </tr>

                <tr>
                  <td>
                    <button data-topicbank_event="new_role" class="btn btn-link" type="button">
                      <span class="glyphicon glyphicon-plus"></span>
                      Add a role
                    </button>
                  </td>
                </tr>
                
              </table>
              
            </td>
            <td>
              <?php if (count($association[ 'scope' ]) === 0) $association[ 'scope' ][ ] = ''; foreach ($association[ 'scope' ] as $scope) { ?>
              <input type="text" name="associations[<?=$i?>][scope][]" value="<?=htmlspecialchars($scope)?>" />
              <?php } ?>
            </td>
            <td>
              <button class="btn btn-link" type="button" data-topicbank_event="remove" data-topicbank_remove_hide="associations[<?=$i?>][delete]">
                <span class="glyphicon glyphicon-remove"></span>
              </button>
            </td>
          </tr>
      
        <?php } $i++; ?>

          <tr data-topicbank_template="new_association" class="hidden" data-topicbank_counter_value="<?=$i?>" data-topicbank_counter_name="TOPICBANK_COUNTER1">
            <td>
              <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="association_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                <span data-topicbank_element="name">[Choose association type]</span>:
                <span class="caret"></span>
              </button>
              <input type="hidden" name="associations[TOPICBANK_COUNTER1][type]" value="" data-topicbank_element="id" />
              
              <input type="hidden" name="associations[TOPICBANK_COUNTER1][id]" value="" />
              <input type="hidden" name="associations[TOPICBANK_COUNTER1][delete]" value="0" />
            </td>
            <td>
            
              <table>
              
                <tr>
                  <td>
                    <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="role_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                      <span data-topicbank_element="name">[Choose role type]</span>:
                      <span class="caret"></span>
                    </button>
                    <input type="hidden" name="associations[TOPICBANK_COUNTER1][roles][0][type]" value="" data-topicbank_element="id" />
                  </td>
                  <td>
                    (This topic)
                    <input type="hidden" name="associations[TOPICBANK_COUNTER1][roles][0][player]" value="{this_topic}" data-topicbank_element="id" />
                  </td>
                  <td>
                  </td>
                </tr>

                <tr data-topicbank_template="new_role" class="hidden" data-topicbank_counter_value="1" data-topicbank_counter_name="TOPICBANK_COUNTER2">
                  <td>
                    <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="role_type" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                      <span data-topicbank_element="name">[Choose role type]</span>:
                      <span class="caret"></span>
                    </button>
                    <input type="hidden" name="associations[TOPICBANK_COUNTER1][roles][TOPICBANK_COUNTER2][type]" value="" data-topicbank_element="id" />
                  </td>
                  <td>
                    <button class="btn btn-link" data-topicbank_event="show_choose_topic_dialog" data-topicbank_what="role_player" type="button" data-toggle="modal" data-target="#choose_topic_dialog">
                      <span data-topicbank_element="name">[Choose role player]</span>:
                      <span class="caret"></span>
                    </button>
                    <input type="hidden" name="associations[TOPICBANK_COUNTER1][roles][TOPICBANK_COUNTER2][player]" value="" data-topicbank_element="id" />
                  </td>
                  <td>
                    <button class="btn btn-link" type="button" data-topicbank_event="remove">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                  </td>
                </tr>

                <tr>
                  <td>
                    <button data-topicbank_event="new_role" class="btn btn-link" type="button">
                      <span class="glyphicon glyphicon-plus"></span>
                      Add a role
                    </button>
                  </td>
                </tr>
                
              </table>
              
            </td>
            <td>
              <input type="text" name="associations[TOPICBANK_COUNTER1][scope][]" value="" />
            </td>
            <td>
              <button class="btn btn-link" type="button" data-topicbank_event="remove">
                <span class="glyphicon glyphicon-remove"></span>
              </button>
            </td>
          </tr>

          <tr>
            <td>
              <button data-topicbank_event="new_association" class="btn btn-link" type="button">
                <span class="glyphicon glyphicon-plus"></span>
                Add an association
              </button>
            </td>
          </tr>

        </table>

      </div>

      </form>
          
      <!-- Footer -->

      <div class="footer">
        <p>TopicBank 0.1 by Tim Strehle</p>
      </div>

      <!-- "Choose topic" dialog, initially hidden -->

      <div class="modal" id="choose_topic_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="container modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title" id="myModalLabel">Choose a property</h4>
            </div>
            <div class="modal-body">
            </div>
          </div>
        </div>
      </div>
    
    </div> <!-- /container -->
    
    <script src="<?=$tpl[ 'topicbank_static_base_url' ]?>jquery.min.js"></script>
    <script src="<?=$tpl[ 'topicbank_static_base_url' ]?>bootstrap/js/bootstrap.min.js"></script>
    
    <script>
    // <![CDATA[
    
    var topicbank_base_url = '<?=$tpl[ 'topicbank_base_url' ]?>';
    var topicbank_static_base_url = '<?=$tpl[ 'topicbank_static_base_url' ]?>';
    
    $(document).ready(function() 
    {
        var _private = { };
        
        _private.addFormSection = function($elem_to_clone)
        {
            var $clone, counter_value, counter_name, counter_pattern;
            
            $clone = $elem_to_clone.clone();
            
            $clone
                .attr('id', '')
                .insertBefore($elem_to_clone)
                .removeClass('hidden')
                .find('input').first().focus();
                
            counter_value = $elem_to_clone.data('topicbank_counter_value');
            counter_name = $elem_to_clone.data('topicbank_counter_name');
            
            counter_pattern = new RegExp(counter_name);
            
            if (counter_value !== undefined)
            {
                counter_value = parseInt(counter_value, 10);
                
                $clone.find('input').each(function(i, item)
                {
                    var $item = $(item);
                    var elem_name = $item.attr('name');
                    
                    if ((elem_name === undefined) || (elem_name.length < 1))
                    {
                        return;
                    }
                    
                    $item.attr('name', elem_name.replace(counter_pattern, counter_value));
                });
                
                $elem_to_clone.data('topicbank_counter_value', (counter_value + 1));
            }
        };

        $([ 'type', 'name', 'subject_locator', 'subject_identifier', 'occurrence', 'association', 'role' ]).each(function(i, item)
        {
            var button_selector = 'button[data-topicbank_event="new_' + item + '"]';
            var selector_to_clone = 'tr[data-topicbank_template="new_' + item + '"]';
            
            $('#topicbank_form_edit').on('click', button_selector, function(e)
            {
                var $elem_to_clone = $(e.target).closest('tr').siblings(selector_to_clone).last();
                _private.addFormSection($elem_to_clone);
            });
        });

        $('#topicbank_form_edit').on('click', 'button[data-topicbank_event="remove"]', function(e)
        {
            var $button = $(e.target);
            var $tr = $button.closest('tr');
            
            var hidden_field_name = $button.data('topicbank_remove_hide');
            
            if ((hidden_field_name !== undefined) && (hidden_field_name.length > 0))
            {
                $tr.find('[name="' + hidden_field_name + '"]').val('1');
                $tr.addClass('hidden');
            }
            else
            {
                $tr.remove();
            }
        });

        $('#topicbank_form_edit').on('click', 'button[data-topicbank_event="show_choose_topic_dialog"]', function(e)
        {
            var $choose_topic_dialog, $dialog_body, what;

            $choose_topic_dialog = $('#choose_topic_dialog');
            $dialog_body = $choose_topic_dialog.find('div.modal-body');
            
            _private.choose_topic_dialog_opener = e.target;
            
            what = $(e.target).data('topicbank_what');
            
            $dialog_body.empty().load(topicbank_base_url + 'choose_topic_dialog?what=' + encodeURIComponent(what));
        });

        $('#choose_topic_dialog').on('click', 'button[data-topicbank_element="topic"]', function(e)
        {
            var $target, $target_id, $target_name, $source, $source_id, $source_name;
            
            if (_private.choose_topic_dialog_opener === undefined)
            {
                return;
            }
            
            $source = $(e.target);
            
            $source_id = $source.find('span[data-topicbank_element="id"]');
            $source_name = $source.find('span[data-topicbank_element="name"]');
            
            $target = $(_private.choose_topic_dialog_opener).closest('td');
            
            $target_id = $target.find('input[data-topicbank_element="id"]');
            $target_name = $target.find('span[data-topicbank_element="name"]');
            
            $target_id.val($source_id.text());
            $target_name.html($source_name.html());
            
            $('#choose_topic_dialog').modal('hide');
        });

        $('#choose_topic_dialog').on('click', 'button[data-topicbank_element="create_topic"]', function(e)
        {
            var new_name, new_type, $new_name_input, $new_type_input, $target, $target_id, $target_name;
            
            if (_private.choose_topic_dialog_opener === undefined)
            {
                return;
            }
            
            $new_name_input = $('#choose_topic_dialog').find('input[data-topicbank_element="name"]');
            new_name = $new_name_input.val();
            
            $new_type_input = $('#choose_topic_dialog').find('select[data-topicbank_element="type"]');            
            new_type = $new_type_input.val();

            $.ajax(
            {
                url: topicbank_base_url + 'ajax_create_topic',
                cache: false,
                data:
                {
                    name: new_name,
                    type: new_type
                },
                dataType: 'json'
            }).done(function(data)
            {
                $target = $(_private.choose_topic_dialog_opener).closest('td');
            
                $target_id = $target.find('input[data-topicbank_element="id"]');
                $target_name = $target.find('span[data-topicbank_element="name"]');
            
                $target_id.val(data.id);
                $target_name.html(data.name);
            
                $('#choose_topic_dialog').modal('hide');
            });
        });

    });
    
    // ]]>
    </script>

  </body>
</html>