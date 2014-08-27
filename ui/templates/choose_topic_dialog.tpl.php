<div class="row">
  <div class="col-md-4">
    <h4>Recent</h4>
    <ul class="nav nav-pills nav-stacked">
      <?php foreach ($tpl[ 'recent' ] as $topic_arr) { ?>
      <li>
        <button data-topicbank_element="topic" class="btn btn-link" type="button">
          <span data-topicbank_element="name"><?=htmlspecialchars($topic_arr[ 'label' ])?></span>
          <span data-topicbank_element="id" class="hidden"><?=htmlspecialchars($topic_arr[ 'id' ])?></span>
        </button>
      </li>
      <?php } ?>
    </ul>
  </div>
  <div class="col-md-4">
    <h4>Search</h4>
    <form id="topicbank_choose_topic_dialog_searchform">
      <select size="1" name="search_type" data-topicbank_element="search_type">
        <option value="">(Any type)</option>
        <?php foreach ($tpl[ 'topic_types' ] as $topic_arr) { ?>
        <option value="<?=htmlspecialchars($topic_arr[ 'id' ])?>"><?=htmlspecialchars($topic_arr[ 'label' ])?></option>
        <?php } ?>
      </select>
      <input type="text" name="search_name" data-topicbank_element="search_name" />
      <button class="btn btn-default" type="submit">Search</button>
    </form>
    <ul class="nav nav-pills nav-stacked" data-topicbank_element="search_results">
    </ul>
  </div>
  <div class="col-md-4">
    <h4>Create new</h4>
    <form id="topicbank_choose_topic_dialog_createform">
      <select size="1" name="create_type" data-topicbank_element="create_type">
        <option value="">(No type)</option>
        <?php foreach ($tpl[ 'topic_types' ] as $topic_arr) { ?>
        <option value="<?=htmlspecialchars($topic_arr[ 'id' ])?>"><?=htmlspecialchars($topic_arr[ 'label' ])?></option>
        <?php } ?>
      </select>
      <input type="text" name="create_name" data-topicbank_element="create_name" />
      <button class="btn btn-default" type="submit">Create</button>
    </form>
  </div>
</div>
