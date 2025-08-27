<p>Ciao <?php echo esc_html($vendor_name); ?>,</p>
<p>Hai ricevuto un ordine collettivo dal Gruppo #<?php echo intval($group_id); ?>.</p>
<p>Spedisci i seguenti prodotti al punto di ritiro:</p>
<p><strong><?php echo esc_html($pickup_point); ?></strong></p>
<ul>
<?php foreach ($vendor_items as $item): ?>
    <li><?php echo esc_html($item['name']); ?> × <?php echo intval($item['qty']); ?></li>
<?php endforeach; ?>
</ul>
