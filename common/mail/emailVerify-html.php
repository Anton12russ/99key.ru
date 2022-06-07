<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['user/verify-email', 'token' => $user->verification_token]);
?>
<div class="es-wrapper-color" style="background-color:#F6F6F6">
		<!--[if gte mso 9]><v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t"> <v:fill type="tile" color="#f6f6f6"></v:fill> </v:background><![endif]-->
		<table cellpadding="0" cellspacing="0" class="es-wrapper" width="100%"
			style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top">
			<tr>
				<td valign="top" style="padding:0;Margin:0">
					<table cellpadding="0" cellspacing="0" class="es-content" align="center"
						style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
						<tr>
							<td align="center" style="padding:0;Margin:0">
								<table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0"
									cellspacing="0"
									style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
									<tr>
										<td align="left"
											style="Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px">
											<table cellpadding="0" cellspacing="0" width="100%"
												style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
												<tr>
													<td align="center" valign="top"
														style="padding:0;Margin:0;width:560px">
														<table cellpadding="0" cellspacing="0" width="100%"
															role="presentation"
															style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
															<tr>
																<td align="left"
																	style="padding:0;Margin:0;padding-bottom:15px">
																	<h2
																		style="Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:'times new roman', times, baskerville, georgia, serif;font-size:24px;font-style:normal;font-weight:normal;color:#333333;text-align:center">
																		<strong>Спасибо за регистрацию на
																			1TU.ru</strong>
																	</h2>
																</td>
															</tr>
															<tr>
																<td align="center"
																	style="padding:0;Margin:0;font-size:0px"><img
																		class="adapt-img"
																		src="https://1tu.ru/images_all/verifyemail.jpg" alt
																		style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
																		width="560"></td>
															</tr>
															<tr>
																<td align="left"
																	style="padding:0;Margin:0;padding-top:20px">
																	<p
																		style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:16px">
																		Здравствуйте, <?= $user->username ?><br>Подтвердите ваш email по ссылке - <a
																			target="_blank" href="<?=$verifyLink?>"
																			style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#1376C8;font-size:16px">Активация
																			аккаунта</a>&nbsp;- что бы пользоваться
																		всеми возможностями сервиса - 1TU.RU<br><hr><br>
																		<?= $verifyLink ?><br>
																		<hr>
																		<br>
																	</p>
																</td>
															</tr>
															<tr>
																<td align="left" bgcolor="#fbfefa"
																	style="padding:0;Margin:0;padding-top:20px">
																	<p
																		style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px;text-align:right">
																		<strong><a target="_blank"
																				href="https://1tu.ru/Polzovatelskoe-soglashenie.htm"
																				style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#1376C8;font-size:14px">Пользовательское
																				соглашение</a><br><a target="_blank"
																				href="https://1tu.ru/Politika-konfidencialnosti.htm"
																				style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#1376C8;font-size:14px">Политика&nbsp;конфиденциальности</a></strong>
																	</p>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table cellpadding="0" cellspacing="0" class="es-footer" align="center"
						style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
						<tr>
							<td align="center" style="padding:0;Margin:0">
								<table class="es-footer-body" align="center" cellpadding="0" cellspacing="0"
									style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:600px">
									<tr>
										<td align="left"
											style="Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px">
											<table cellpadding="0" cellspacing="0" width="100%"
												style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
												<tr>
													<td align="center" valign="top"
														style="padding:0;Margin:0;width:560px">
														<table cellpadding="0" cellspacing="0" width="100%"
															role="presentation"
															style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
															<tr>
																<td align="center"
																	style="padding:20px;Margin:0;font-size:0">
																	<table border="0" width="75%" height="100%"
																		cellpadding="0" cellspacing="0"
																		role="presentation"
																		style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
																		<tr>
																			<td
																				style="padding:0;Margin:0;border-bottom:1px solid #CCCCCC;background:none;height:1px;width:100%;margin:0px">
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															<tr>
																<td align="center"
																	style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px">
																	<p
																		style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:11px">
																		<a href="https://1tu.ru/My-v-SocSetyah.htm"
																			style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#1376C8;font-size:11px">Мы
																			в Соц.Сетях</a>
																	</p>
																</td>
															</tr>
															<tr>
																<td align="center"
																	style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px">
																	<p
																		style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:11px">
																		© 2021 <a target="_blank" href="https://1tu.ru"
																			style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#1376C8;font-size:11px">1TU.ru</a>
																	</p>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table cellpadding="0" cellspacing="0" class="es-content" align="center"
						style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
						<tr>
							<td align="center" style="padding:0;Margin:0">
								<table class="es-content-body" align="center" cellpadding="0" cellspacing="0"
									style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:600px">
									<tr>
										<td align="left"
											style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-bottom:30px">
											<table cellpadding="0" cellspacing="0" width="100%"
												style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
												<tr>
													<td align="center" valign="top"
														style="padding:0;Margin:0;width:560px">
														<table cellpadding="0" cellspacing="0" width="100%"
															style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
															<tr>
																<td align="center"
																	style="padding:0;Margin:0;display:none"></td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
