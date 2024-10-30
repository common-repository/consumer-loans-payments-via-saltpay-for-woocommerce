<fieldset>
  <?php if($message): ?>
    <div class="saltpay-loans-message">
      <p><?php echo esc_html($message);?></p>
    </div>
  <?php else: ?>
    <ul class="saltpay-loans">
      <?php foreach($loans as $loan): ?>
        <li class="loan">
          <input type="hidden" name="max_number_of_payments[<?php echo esc_attr($loan['loanTypeId']); ?>]" value="<?php echo esc_attr($loan['maxNumberOfPayments']);?>">
          <div class="loan-choice field choice">

            <input type="radio"
                   id="loan-<?php echo esc_attr($loan['loanTypeId']); ?>"
                   name="loan_payment_id"
                   class="radio loan-item loanTypeId-item"
                   value="<?php echo esc_attr($loan['loanTypeId']); ?>"
                   <?php echo (count($loans)==1) ? 'checked="checked"' : ''; ?> />
            <label class="label loan-id-label" for="loan-<?php echo esc_attr($loan['loanTypeId']); ?>">
                <span class="loan-name"><?php echo esc_html($loan['paymentName']); ?></span><button class="loan-info-toggle" type="button"><span class="btn-text">More</span></button>
            </label>
          </div>
          <div class="loan-info">
            <span class="loan-info-text"><?php echo esc_html($loan['paymentInfo']); ?></span>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</fieldset>
