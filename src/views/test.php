<h3>Test view</h3>

<span style="color: #5d77c0;">
<?php echo date('Y-m-d H:i:s'); ?>
</span>

<span style="color: #8a4568;">
    <!--  $msg should be set in controller action  -->
    <p>p:<?= $msg;?></p>
</span>

<ul>
<!--  example with array  -->
<?php foreach ($values as $value) {echo "<li>$value</li>\n";} ?>
</ul>
