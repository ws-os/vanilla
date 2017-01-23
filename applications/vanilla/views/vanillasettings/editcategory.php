<?php if (!defined('APPLICATION')) exit();
/** @var Gdn_Form $form */
$form = $this->Form;
echo heading($this->data('Title'), '', '', [], '/vanilla/settings/categories');
echo $form->open(array('enctype' => 'multipart/form-data'));
echo $form->errors();
echo $form->hidden('ParentCategoryID');
helpAsset(sprintf(t('About %s'), t('Categories')), t('Categories are used to organize discussions.', 'Categories allow you to organize your discussions.'));
?>
<ul>
    <li class="form-group">
        <div class="label-wrap">
            <?php echo $form->label('Category', 'Name'); ?>
        </div>
        <div class="input-wrap">
            <?php echo $form->textBox('Name'); ?>
        </div>
    </li>
    <li class="form-group">
        <div class="label-wrap">
            <?php echo wrap(t('Category Url:'), 'strong'); ?>
        </div>
        <div id="UrlCode" class="input-wrap category-url-code">
            <?php
            echo '<div class="category-url">';
            echo Gdn::request()->Url('categories', true);
            echo '/';
            echo wrap(htmlspecialchars($form->getValue('UrlCode')));
            echo '</div>';
            echo $form->textBox('UrlCode');
            echo ($form->getValue('UrlCode')) ? '/' : '';
            echo anchor(t('edit'), '#', 'Edit btn btn-link');
            echo anchor(t('OK'), '#', 'Save btn btn-primary');
            ?>
        </div>
    </li>
    <li class="form-group">
        <div class="label-wrap">
            <?php echo $form->label('Description', 'Description'); ?>
        </div>
        <div class="input-wrap">
            <?php echo $form->textBox('Description', array('MultiLine' => TRUE)); ?>
        </div>
    </li>
    <li class="form-group">
        <div class="label-wrap">
            <?php echo $form->label('Css Class', 'CssClass'); ?>
        </div>
        <div class="input-wrap">
            <?php echo $form->textBox('CssClass', array('MultiLine' => FALSE)); ?>
        </div>
    </li>
    <?php echo $form->imageUploadPreview(
        'Photo', t('Photo'), '', 'vanilla/settings/deletecategoryphoto/'.$this->Category->CategoryID
    ); ?>
    <?php echo $form->Simple(
        $this->data('_ExtendedFields', array()));
    ?>
    <li class="form-group">
        <div class="label-wrap">
            <?php echo $form->label('Display As', 'DisplayAs'); ?>
        </div>
        <div class="input-wrap">
            <?php echo $form->dropDown('DisplayAs', $this->data('DisplayAsOptions'), ['Wrap' => true]); ?>
        </div>
    </li>
    <li class="form-group">
        <?php echo $form->toggle('HideAllDiscussions', 'Hide from the recent discussions page.'); ?>
    </li>
    <?php if ($this->ShowCustomPoints): ?>
        <li class="form-group">
            <?php echo $form->toggle('CustomPoints', 'Track points for this category separately.'); ?>
        </li>
    <?php endif; ?>
    <?php if ($this->data('Operation') == 'Edit'): ?>
        <li class="form-group">
            <?php
            echo $form->toggle('Archived', 'This category is archived.');
            ?>
        </li>
        <?php $this->fireEvent('AfterCategorySettings'); ?>
    <?php endif; ?>
    <?php if (count($this->PermissionData) > 0) { ?>
        <li id="Permissions" class="form-group">
            <?php echo $form->toggle('CustomPermissions', 'This category has custom permissions.'); ?>
        </li>
    <?php } ?>
</ul>
<?php
echo '<div class="CategoryPermissions">';
if (count($this->data('DiscussionTypes')) > 1) {
    echo '<div class="P DiscussionTypes form-group">';
    echo '<div class="label-wrap">';
    echo $form->label('Discussion Types');
    echo '</div>';
    echo '<div class="checkbox-list input-wrap">';
    foreach ($this->data('DiscussionTypes') as $Type => $Row) {
        echo $form->CheckBox("AllowedDiscussionTypes[]", val('Plural', $Row, $Type), array('value' => $Type));
    }
    echo '</div>';
    echo '</div>';
}

echo $form->Simple(
    $this->data('_PermissionFields', array()),
    array('Wrap' => array('<div class="form-group">', '</div>'), 'ItemWrap' => array('<div class="input-wrap">', '</div>')));

echo '<div class="padded">'.sprintf(t('%s: %s'), t('Check all permissions that apply for each role'), '').'</div>';
echo $form->CheckBoxGridGroups($this->PermissionData, 'Permission');
echo '</div>';
echo $form->close('Save');
