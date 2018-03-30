<?php

if(!empty($_POST["ajax"])) {
	if($_POST["do"] == "cal") {
		$interest = (double)$_POST["interest"]; // อัตราดอกเบี้ย
		$loan = (double)$_POST["loan"]; // จำนวนเงินกู้
		$pay_type = $_POST["pay_type"]; // ปรเภท งวด เงิน
		$period = (double)$_POST["period"]; // จำนวน งวด  หรือ เงิน แล้วแต่ type
		$day = (double)$_POST["day"];
		$month = (double)$_POST["month"];
		$year  = (double)$_POST["year"];
		$period_type= (double)$_POST["period_type"]; // ประเภท ต้นคงที่ ต้นดอก
		$loan_type= $_POST["loan_type"]; // ประเภทการกู้เงิน
		if($loan_type == '3' || $loan_type == '4'){
			$cal_type = '2';
		}else{
			$cal_type = '1';
		}
		
		if($cal_type == '2'){
			if($day > 15){
				$month++;
				if($month > 12){
					$month = 1;
					$year++;
				}
			}
		}else if($cal_type == '1'){
			$month++;
			if($month > 12){
				$month = 1;
				$year++;
			}
		}

		$pay_period = $loan / $period;
		$a = ceil($pay_period/10)*10;
		
		$daydiff = date('t') - $day;

		ob_start(); ?>
		<div id="cal_table">
		<table class="table table-condensed">
			<thead>
				<tr>
					<th class="text-center" style="width: 8%;">งวดที่</th>
					<th class="text-right"  style="width: 12%;">เงินต้นคงเหลือ</th>
					<th class="text-right"  style="width: 15%;">วันที่หัก</th>
					<th class="text-right"  style="width: 14%;">จำนวนวัน</th>
					<th class="text-right"  style="width: 9%;">ดอกเบี้ย</th>
					<th class="text-right"  style="width: 14%;">เงินต้นชำระ</th>
					<th class="text-right"  style="width: 15%;">รวมชำระต่อเดือน</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$loan_remain = $loan;
				$is_last = FALSE;
				$total_loan_pri = 0;
				$total_loan_int = 0;
				$total_loan_pay = 0;
				$d = $period - 1;
				for ($i=1; $i <= $period; $i++) {
					if($loan_remain <= 0 ){ break; }
					if($pay_type == 1) {
						if ($period_type == 1) {
									if ($month > 12) {
											$month = 1;
											$year += 1;
									}
									$loan_pri = $a;
									$nummonth = cal_days_in_month(CAL_GREGORIAN, $month , $year);
									$summonth = $nummonth;
									$daydiff = 31 - $day;
									if ($i == 1) {
										if ($daydiff >= 0) {
												if ($day <= 10) {
													$summonth -=  $day;
													$summonth += 1;
												} else if ($day >= 11 && $day <= 31) {
													$month += 1;
													if ($month > 12) {
															$month = 1;
															$year += 1;
													}
													$nummonth = cal_days_in_month(CAL_GREGORIAN, $month , $year);
													$summonth = $nummonth;
													$summonth = $daydiff + 31;
												}
										 }
									}
									$loan_int = $loan_remain * ($interest / (365 / $summonth)) / 100;
									$loan_pay = $loan_pri + $loan_int;
									$loan_remain -= $loan_pri;
						} else if ($period_type == 2) {
							if ($month > 12) {
									$month = 1;
									$year += 1;
							}
							$nummonth = cal_days_in_month(CAL_GREGORIAN, $month , $year);
							$summonth = $nummonth;
							$daydiff = 31 - $day;
							if ($i == 1) {
								if ($daydiff >= 0) {
										if ($day <= 10) {
											$summonth -=  $day;
											$summonth += 1;
										} else if ($day >= 11 && $day <= 31) {
											$month += 1;
											$nummonth = cal_days_in_month(CAL_GREGORIAN, $month , $year);
											$summonth = $nummonth;
											$summonth = $daydiff + 31;
										}
								 }
							}
							$loan_pri = $period;
							$loan_int = $loan_remain * ($interest / (365 / $summonth)) / 100;
							$loan_pay = $loan_pri + $loan_int;
							$loan_remain -= $loan_pri;
					}
				}
					else if($pay_type == 2) {
						if ($month > 12) {
								$month = 1;
								$year += 1;
						}
						$nummonth = cal_days_in_month(CAL_GREGORIAN, $month , $year);
						$summonth = $nummonth;
						$daydiff = 31 - $day;
						if ($i == 1) {
							if ($daydiff >= 0) {
									if ($day <= 10) {
										$summonth -=  $day;
										$summonth += 1;
									} else if ($day >= 11 && $day <= 31) {
										$month += 1;
										$nummonth = cal_days_in_month(CAL_GREGORIAN, $month , $year);
										$summonth = $nummonth;
										$summonth = $daydiff + 31;
									}
							 }
						}
						$interest_m = $interest/1200;
						$result = $loan * $interest_m * (pow((1 + $interest_m),$period) / (pow((1 + $interest_m),$period) -1));
						$loan_pri = $period;
						$loan_int = $loan_remain * ($interest / (365 / $summonth)) / 100;
						$loan_pay = $result;
						$loan_pri = $loan_pay - $loan_int;
						$loan_remain -= $loan_pri;
					}

					if($loan_remain <= 0) {
						$loan_pri += $loan_remain;
						$loan_pay = $loan_pri + $loan_int;
						$loan_remain = 0;
						@$count = $count + 1;
					}

					$sumloan = $loan_remain + $loan_pri;
					$sumloanarr[] = $loan_remain + $loan_pri;
					$sumint[] = $loan_int;
					if ($i == $period) {
						$loan_pri = $sumloanarr[$d];
						$loan_pay = $loan_pri + $loan_int;
					}

					@$total_loan_int += $loan_int;
					@$total_loan_pri += $loan_pri;
					@$total_loan_pay += $loan_pay;

					@$total_loan_pri_m += $loan_pri;
					@$total_loan_int_m += $loan_int;
					@$total_loan_pay_m += $loan_pay;

					?>

					<tr>
						<td class="text-center">
							<?php echo $i; ?>
							<input type="hidden" name="data[coop_loan_period][<?php echo $i; ?>][period_count]" value="<?php echo $i; ?>">
						</td>
						<td class="text-right">
							<?php echo number_format(($sumloan) , 2); ?>
							<input type="hidden" name="data[coop_loan_period][<?php echo $i; ?>][outstanding_balance]" value="<?php echo number_format($sumloan,2,".",""); ?>">
						</td>
						<td class="text-right">
							<?php 
							if((int)$month == '2'){
								$nummonth = '28';
							}
							echo $nummonth." / ".$month." / ".$year;
							if($i==1){ ?>
								<input type="hidden" id="first_date_period_label" value="<?php echo $nummonth."/".$month."/".$year; ?>">
								<input type="hidden" id="first_date_period" value="<?php echo ($year-543)."-".sprintf('%02d',$month)."-".$nummonth; ?>">
								<input type="hidden" id="first_pay" value="<?php echo number_format($loan_pay,2); ?>">
							<?php }
							if($i==2){ ?>
								<input type="hidden" id="second_date_period_label" value="<?php echo $nummonth."/".$month."/".$year; ?>">
								<input type="hidden" id="second_date_period" value="<?php echo ($year-543)."-".sprintf('%02d',$month)."-".$nummonth; ?>">
								<input type="hidden" id="second_pay" value="<?php echo number_format($loan_pay,2); ?>">
							<?php } ?>
							
							<input type="hidden" name="data[coop_loan_period][<?php echo $i; ?>][date_period]" value="<?php echo ($year-543)."-".sprintf('%02d',$month)."-".$nummonth; ?>">
						</td>
						<th class="text-right">
							<?php echo $summonth?>
							<input type="hidden" name="data[coop_loan_period][<?php echo $i; ?>][date_count]" value="<?php echo $summonth; ?>">
						</th>
						<td class="text-right">
							<?php echo number_format($loan_int, 2); ?>
							<input type="hidden" name="data[coop_loan_period][<?php echo $i; ?>][interest]" value="<?php echo number_format($loan_int,2,".",""); ?>">
						</td>
						<td class="text-right">
							<?php echo number_format($loan_pri, 2); ?>
							<input type="hidden" name="data[coop_loan_period][<?php echo $i; ?>][principal_payment]" value="<?php echo number_format($loan_pri,2,".",""); ?>">
						</td>
						<td class="text-right">
							<?php echo number_format($loan_pay, 2); ?>
							<input type="hidden" name="data[coop_loan_period][<?php echo $i; ?>][total_paid_per_month]" value="<?php echo number_format($loan_pay,2,".",""); ?>">
						</td>
					</tr>

					<?php

					if($is_last) {
						break;
					}
					$month++;
					?>
					<?php if ($month > 12) { ?>
					<tr style="font-weight: bold;">
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center">รวมปี</td>
						<td class="text-center"><?php echo $year?></td>
						<td class="text-right"><?php echo number_format($total_loan_int_m, 2); ?></td>
						<td class="text-right"><?php echo number_format($total_loan_pri_m, 2); ?></td>
						<td class="text-right"><?php echo number_format($total_loan_pay_m, 2); ?></td>
					</tr>
					<?php if ($month > 12) { $total_loan_int_m = 0;  $total_loan_pri_m = 0; $total_loan_pay_m = 0; } ?>
					<?php } else if (($i-1) == $d) { ?>
						<tr style="font-weight: bold;">
							<td class="text-center"></td>
							<td class="text-center"></td>
							<td class="text-center">รวมปี</td>
							<td class="text-center"><?php echo $year?></td>
							<td class="text-right"><?php echo number_format($total_loan_int_m, 2); ?></td>
							<td class="text-right"><?php echo number_format($total_loan_pri_m, 2); ?></td>
							<td class="text-right"><?php echo number_format($total_loan_pay_m, 2); ?></td>
						</tr>
						<?php $is_last = TRUE; } ?>

			<?php } ?>
<input type="hidden" id="last_period" value="<?php echo date('Y-m-t',strtotime('-1 month',strtotime(($year-543)."-".$month."-".$nummonth))); ?>">
				<tr style="font-weight: bold;">
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-right"></td>
					<td class="text-right"> รวม </td>
					<td class="text-right">
						<?php echo number_format($total_loan_int, 2); ?>
						<input type="hidden" name="data[coop_loan][loan_interest_amount]" value="<?php echo number_format($total_loan_int,2,".",""); ?>">
					</td>
					<td class="text-right">
						<?php echo number_format($total_loan_pri, 2); ?>
						<input type="hidden" name="data[coop_loan][loan_amount_balance]" value="<?php echo number_format($total_loan_pri,2,".",""); ?>">
					</td>
					<td class="text-right">
						<?php echo number_format($total_loan_pay, 2); ?>
						<input type="hidden" name="data[coop_loan][loan_amount_total]" value="<?php echo number_format($total_loan_pay,2,".",""); ?>">
						<input type="hidden" name="data[coop_loan][loan_amount_total_balance]" value="<?php echo number_format($total_loan_pay,2,".",""); ?>">
					</td>
				</tr>
			</tbody>
		</table>
		</div>
		<input type="hidden" id="max_period" value="<?php echo $i-1; ?>">
		<input type="hidden" id="already_cal" value="1">
		<?php
		$loan_table = ob_get_contents();
		ob_end_clean();
		$is_error = FALSE;
		?>
		<?php
		if(!$is_error) { ?>
			<?php
			echo $loan_table;
			?>
			<div class="text-center p-v-xxl hidden-print">
				<button type="button" class="btn btn-primary btn-calculate" onclick="printElem('cal_table');">พิมพ์</button>
			</div>
			<?php
		}
	}
	exit;
}
##### END AJAX #####
