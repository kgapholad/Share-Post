<?php require APPROOT .'/views/inc/header.php'; ?>
<br>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<br>
<h2 class="text-center"><?php echo $data['post']->title; ?></h2>
<div class="bg-secondary text-white p-2 mb-3">
    Written by <?php echo $data['user']->name; ?> on <?php echo $data['post']->created_at; ?>
</div>
<p><?php echo $data['post']->body; ?></p>
<?php if($data['post']->user_id == $_SESSION['user_id']) : ?>
<hr>
<a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>" class="btn btn-primary puul"><i class="fa fa-edit"></i> Edit</a>
<form action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post']->id; ?>" class="pull-right" method="POST">
<input type="Submit" value="Delete" class="btn btn-danger">
</form>
<hr>
<?php endif; ?>
<?php require APPROOT .'/views/inc/footer.php'; ?>