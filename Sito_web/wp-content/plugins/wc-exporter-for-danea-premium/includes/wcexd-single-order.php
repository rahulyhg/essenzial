
<?php
/*
WOOCOMMERCE EXPORTER FOR DANEA - PREMIUM | SINGOLO ORDINE
*/


//FORMATTAZIONE DATA ORDINE
$originalDate = $order->post_date;
$newDate = date("Y-m-d", strtotime($originalDate));
$customer_name =  WCtoDanea::order_details($order->ID, '_billing_first_name') . ' ' . WCtoDanea::order_details($order->ID, '_billing_last_name');
$shipping_name =  WCtoDanea::order_details($order->ID, '_shipping_first_name') . ' ' . WCtoDanea::order_details($order->ID, '_shipping_last_name');

//Recupero i nomi dei campi C.Fiscale e P.IVA
$cf_name = '_' . WCtoDanea::get_italian_tax_fields_names('cf_name');
$pi_name = '_' . WCtoDanea::get_italian_tax_fields_names('pi_name');
/*<CustomerFiscalCode><?php echo WCtoDanea::order_details($order->ID, $cf_name); ?></CustomerFiscalCode> */

/*
<CustomerWebLogin><?php echo (WCtoDanea::order_details($order->ID, '_customer_user') == 0) ? '' : WCtoDanea::order_details($order->ID, '_customer_user'); ?></CustomerWebLogin>
*/
?>

<Document>
    <DocumentType>C</DocumentType>
    <CustomerWebLogin></CustomerWebLogin>
    <CustomerName><?php echo (WCtoDanea::order_details($order->ID, '_billing_company')) ? WCtoDanea::order_details($order->ID, '_billing_company') : $customer_name; ?></CustomerName>
    <CustomerAddress><?php echo WCtoDanea::order_details($order->ID, '_billing_address_1').' '.get_post_meta( $order->ID, '_billing_myfield6', true );?></CustomerAddress>
    <CustomerPostcode><?php echo WCtoDanea::order_details($order->ID, '_billing_postcode'); ?></CustomerPostcode>
    <CustomerCity><?php echo WCtoDanea::order_details($order->ID, '_billing_city'); ?></CustomerCity>
    <CustomerProvince><?php echo WCtoDanea::order_details($order->ID, '_billing_state'); ?></CustomerProvince>
    <CustomerCountry><?php echo WCtoDanea::order_details($order->ID, '_shipping_country'); ?></CustomerCountry>
    <CustomerVatCode><?php echo WCtoDanea::order_details($order->ID, $pi_name); ?></CustomerVatCode>
    <CustomerFiscalCode><?php echo get_post_meta( $order->ID, 'codice_fiscale', true ); ?></CustomerFiscalCode>
    <CustomerTel><?php echo WCtoDanea::order_details($order->ID, '_billing_phone'); ?></CustomerTel>
    <CustomerCellPhone></CustomerCellPhone>
    <CustomerEmail><?php echo WCtoDanea::order_details($order->ID, '_billing_email'); ?></CustomerEmail>
    <DeliveryName><?php echo (WCtoDanea::order_details($order->ID, '_shipping_company')) ? WCtoDanea::order_details($order->ID, '_shipping_company') : $shipping_name; ?></DeliveryName>
    <DeliveryAddress><?php echo WCtoDanea::order_details($order->ID, '_shipping_address_1'); ?></DeliveryAddress>
    <DeliveryPostcode><?php echo WCtoDanea::order_details($order->ID, '_shipping_postcode'); ?></DeliveryPostcode>
    <DeliveryCity><?php echo WCtoDanea::order_details($order->ID, '_shipping_city'); ?></DeliveryCity>
    <DeliveryProvince><?php echo WCtoDanea::order_details($order->ID, '_shipping_state'); ?></DeliveryProvince>
    <DeliveryCountry><?php echo WCtoDanea::order_details($order->ID, '_shipping_country'); ?></DeliveryCountry>
    <DeliveryTel></DeliveryTel>
    <DeliveryCellPhone></DeliveryCellPhone>
    <Date><?php echo $newDate; ?></Date>
    <Number><?php echo $order->order_id; ?></Number>
    <Total><?php echo WCtoDanea::order_details($order->ID, '_order_total'); ?></Total>
    <CostDescription><?php echo WCtoDanea::get_shipping_method_name($order->ID); ?></CostDescription>
    <?php
    if(WCtoDanea::order_details($order->ID, '_order_shipping_tax') != 0) {
        $cost_vat_code = number_format(WCtoDanea::order_details($order->ID, '_order_shipping_tax') * 100 / WCtoDanea::order_details($order->ID, '_order_shipping'));
    }
    ?>
    <CostVatCode><?php echo($cost_vat_code) ? $cost_vat_code : '22 SS'; ?></CostVatCode>
    <CostAmount><?php
        $payment_type = WCtoDanea::order_details($order->ID, '_payment_method_title');
        $cost_amount = WCtoDanea::order_details($order->ID, '_order_shipping');
        if ($payment_type == "Contrassegno") {
            $payment_gateway = wc_get_payment_gateway_by_order( wc_get_order( $order->ID ) );
            $cost_amount += $payment_gateway->settings["extra_charges"];
        }
        echo  $cost_amount;

        ?></CostAmount>
    <PricesIncludeVat>true</PricesIncludeVat>
    <PaymentName><?php echo WCtoDanea::order_details($order->ID, '_payment_method_title'); ?></PaymentName>
    <InternalComment><?php echo $order->post_excerpt; ?></InternalComment>
    <CustomField2></CustomField2>
    <SalesAgent></SalesAgent>
    <Rows>
        <?php
        $items = WCtoDanea::get_order_items($order->order_id);
        foreach($items as $item) {

            $get_product_id = WCtoDanea::item_info($item['order_item_id'], '_product_id');
            $variation_id = wc_get_order_item_meta($item['order_item_id'], '_variation_id');

            $from_danea = false;
            if($variation_id) {
                $obj = get_post($variation_id);
                if(strpos($obj->post_name, 'danea') === 0) {
                    $from_danea = true;
                }
            }


            //PARENT OR NOT, USEFUL FOR DANEA VARS SIZE/ COLOR
            if($variation_id && !$from_danea) {
                $item_id = $variation_id;
            } else {
                $item_id = $get_product_id;
            }


            //SKU (FIRST) OR ID
            if(get_post_meta($item_id, '_sku', true)) {
                $product_id = get_post_meta($item_id, '_sku', true);
            } else {
                $product_id = $item_id;
            }

            //IS THE ITEM A BUNDLE?
            $is_bundle = WCtoDanea::item_info($item['order_item_id'], '_bundled_items');


            //IS THE ITEM IN A BUNDLE?
            $is_bundled = WCtoDanea::item_info($item['order_item_id'], '_bundled_by');


            //GET SINGLE ITEM DETAILS
            $item_get_subtotal = WCtoDanea::item_info($item['order_item_id'], '_line_subtotal');
            $item_get_total = WCtoDanea::item_info($item['order_item_id'], '_line_total');
            $item_get_tax = WCtoDanea::item_info($item['order_item_id'], '_line_tax');
            $item_discount = wc_get_order_item_meta($item['order_item_id'], '_wcexd_item_discount');

            $item_price = number_format($item_get_subtotal / WCtoDanea::item_info($item['order_item_id'], '_qty'), 2);


            //PRICE BEFORE DISCOUNT/ ITEM DISCOUNT
            $discount = null;
            if($item_discount && !$is_bundle) {
                $item_price = number_format((($item_get_subtotal * 100) / (100 - $item_discount)), 2);
                $discount = $item_discount . '%';
            }

            //GET SIZE AND COLORS
            $size  = null;
            $color = null;
            if($variation_id) {
                if(get_post_meta($variation_id, 'attribute_pa_size', true)) {
                    $size = "      <Size>" . get_post_meta($variation_id, 'attribute_pa_size', true) . "</Size>\n";
                }
                if(get_post_meta($variation_id, 'attribute_pa_color', true)) {
                    $color = "      <Color>" . get_post_meta($variation_id, 'attribute_pa_color', true) . "</Color>\n";
                }

                $variation_slug = get_post_meta( $variation_id, 'attribute_pa_scegli-profumo', true);

                $variation_description = get_post_meta( $variation_id,'_variation_description')[0];


                if ($variation_slug){
                    global $wpdb;
                    $results = $wpdb->get_results("SELECT * FROM wp_terms WHERE slug = '{$variation_slug}'", ARRAY_A);
                    $variation_title = $results[0]['name'];

                }


            } else {
                $variation_slug = $variation_title = $variation_description = null;
            }

            $cart_discount = false;
            if($item_get_subtotal != $item_get_total) {
                $cart_discount = number_format(($item_get_subtotal - $item_get_total) / $item_get_subtotal * 100);
                $discount = ($item_discount) ? $item_discount . '+' . $cart_discount . '%' : $cart_discount . '%'; // + $price_details['discount']; //ERRATO, DA CONTROLLARE
            } ?>
            <Row>
                <Code><?php echo $product_id; ?></Code>
                <Description><?php
                    if ($variation_id && !empty($variation_description)) echo $variation_description;
                    else if ($variation_id && !empty($variation_slug)) echo $variation_title;
                    else echo sanitize_title($item['order_item_name']); ?></Description>
                <?php echo $size; ?>
                <?php echo $color; ?>
                <Qty><?php echo WCtoDanea::item_info($item['order_item_id'], '_qty'); ?></Qty>
                <Um>pz</Um>
                <Price><?php echo $item_price; ?></Price>
                <VatCode><?php
                    $tax_rate = WCtoDanea::get_tax_rate($item_id);
                    if ($tax_rate == 0) $tax_rate = 22;
                    echo $tax_rate;?></VatCode>
                <Discounts><?php echo $discount; ?></Discounts>
            </Row>
        <?php } ?>
    </Rows>
    <Payment>
        <Advance>false</Advance>
        <Date></Date>
        <Amount><?php echo WCtoDanea::order_details($order->ID, '_order_total'); ?></Amount>
        <Paid><?php echo(get_post_status($order->ID) == 'wc-completed') ? 'true' : 'false'; ?></Paid>
    </Payment>
</Document>