<!-- TODO: Como calcular depreciação de acordo com o valor que vem do endpoint? deprec_mes: 0.92, deprec_contrato: 0.44 sobre valor do veículo -->

<template><div class="samydana-calc" v-loading="carModel.loading" element-loading-text="Buscando melhores preços">
	<form>
		<div class="container">
			<h1 class="samydana-calc-title mb-5">Selecione um carro</h1>

			<div class="row align-items-center">
				<div class="col-12 col-md-4">
					<!-- Selecionar modelo -->
					<div class="form-group">
						<label>Escolha o modelo do carro</label>
						<el-select v-model="carModel.itemSlug" class="d-block" @change="carModelItemChange()" filterable placeholder="Selecione">
							<el-option :value="v.model.slug" :label="v.model.name" v-for="v in veiculos" :key="v.model.slug" v-html="v.model.name"></el-option>
						</el-select>
					</div>

					<code-preview height="300px">
						<pre>carModel: {{ carModel }}</pre>
						<div v-if="carModel.item">
							{{ carModel.item.slug }} <br>
							id: {{ carModel.item.id }} <br>
							codigoLocavia: {{ carModel.item.acf.codigoLocavia }}
						</div>
						<pre>carModel.itemSlug: {{ carModel.itemSlug }}</pre>
						<pre>veiculos: {{ veiculos }}</pre>
					</code-preview>
					
					<div class="form-group">
						<label>Período</label>
						<el-select class="d-block" v-model="carModel.periodMonths" @change="carModelItemChange()">
							<el-option value="">Selecionar período</el-option>
							<el-option :value="p.months" :label="p.title" v-for="p in carModel.periods" :key="p.id">{{ p.title }}</el-option>
						</el-select>
					</div>

					<div class="form-group">
						<label>Uso mensal</label>
						<div class="input-group form-control p-0">
							<select class="form-control border-0 bg-transparent" v-model="carModel.monthKm" @change="carModelItemChange()">
								<option :value="k" v-for="k in [1000, 1500, 2000, 2500, 3000]">{{ k|money('', 0) }}</option>
							</select>
							<div class="input-group-append"><div class="input-group-text border-0">
								Km/mês
							</div></div>
						</div>
					</div>
					
					<div class="form-group">
						<label>Preço Público Mercado 0KM</label>

						<el-tooltip placement="top">
							<div slot="content">Preço de tabela do veículo vendido no site oficial</div>
							<i class="fas fa-question-circle"></i>
						</el-tooltip>

						<ui-money v-model="carModel.itemFipePrice" @input="carModelCalculate()"></ui-money>
						<small class="d-block text-right" v-if="carModel.item">Código FIPE: {{ carModel.itemFipeCode }}</small>
					</div>

					<a href="javascript:;" @click="scrollTo($refs.assinatura)" class="font-weight-bold">Próximo <svg width="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M7.0051 1.20874V12.3787L2.1251 7.49874C1.7351 7.10874 1.0951 7.10874 0.705098 7.49874C0.315098 7.88874 0.315098 8.51874 0.705098 8.90874L7.2951 15.4987C7.6851 15.8887 8.3151 15.8887 8.7051 15.4987L15.2951 8.90874C15.6851 8.51874 15.6851 7.88874 15.2951 7.49874C15.1083 7.31149 14.8546 7.20625 14.5901 7.20625C14.3256 7.20625 14.0719 7.31149 13.8851 7.49874L9.0051 12.3787V1.20874C9.0051 0.65874 8.5551 0.20874 8.0051 0.20874C7.4551 0.20874 7.0051 0.65874 7.0051 1.20874Z" fill="#3CD5EA"/>
					</svg></a>
				</div>

				<div class="col-12 col-md-8 pl-md-5" v-if="carModel.item">
					<transition name="transition-images"
						enter-active-class="animate__animated animate__fadeIn"
						:duration="{enter:200, leave:0}">
						<img :src="carModel.item.acf.imagensTextos.imagemDestaque"
							style="height:250px; width:auto; max-width:100%; height:auto;">
					</transition>
				</div>
			</div>
		</div>

		<div class="pt-5" ref="assinatura"></div>

		<!-- Se temos um carro carregado... -->
		<transition name="transition-cars"
			enter-active-class="animate__animated animate__fadeIn"
			leave-active-class="animate__animated animate__fadeOut">
			<div v-if="carModel.itemFipePrice>0">
				<div style="background: rgba(0, 91, 158, 0.08);">
					<div class="container py-5">
						<!-- Assinatura -->
						<h1 class="samydana-calc-title mb-3">Assinatura</h1>
			
						<div class="row">
							<div class="col-12 col-md-4 form-group">
								<label>Parcelas <span v-if="carModel.periodPrice!=carModel.periodPromo">1ª à {{ props.ultimaParcelaPromocional }}ª</span></label>
								<ui-money v-model="carModel.periodPromo" class="form-control" @input="carModelCalculate()"></ui-money>
							</div>
			
							<div class="col-12 col-md-4 form-group" v-if="carModel.periodPrice!=carModel.periodPromo">
								<label>Parcelas restantes</label>
								<ui-money v-model="carModel.periodPrice" class="form-control" @input="carModelCalculate()"></ui-money>
							</div>
						</div>
			
						<div class="py-4"></div>
						
						<!-- A vista -->
						<h1 class="samydana-calc-title">Pagamento à Vista</h1>
						<div class="row align-items-baseline">
							<div class="col form-group" style="min-width:151px;">
								<label>Seguro 
									<el-tooltip placement="top">
										<i class="fas fa-question-circle"></i>
										<div slot="content" style="max-width:300px;">
											SEGURO <br>
											O custo do seguro é em média de {{ props.seguro }}% do preço de compra do carro. No Livre, você não se preocupa com seguro.
										</div>
									</el-tooltip>
								</label>

								<div class="input-group form-control p-0">
									<input type="number" class="form-control border-0 bg-transparent" v-model="props.seguro" @input="carModelCalculate()">
									<div class="input-group-append"><div class="input-group-text p-1 px-3 border-0">%</div></div>
								</div>

								<small class="d-block" v-html="compResult.seguroLabel"></small>
							</div>

							<div class="col form-group" style="min-width:151px;">
								<label>IPVA</label>
								<ui-money class="form-control" v-model="props.ipva" @input="carModelCalculate()" prefix="" suffix="%" text-align="left"></ui-money>
								<small class="d-block" v-html="compResult.ipvaLabel"></small>
							</div>

							<div class="col form-group" style="min-width:151px;">
								<label>Licenciamento + <br>Seguro Obrigatório</label>
								<ui-money v-model="props.seguro_obrigatorio" @input="carModelCalculate()"></ui-money>
							</div>

							<div class="col form-group" style="min-width:151px;">
								<label>Emplacamento 
									<el-tooltip placement="top">
										<i class="fas fa-question-circle"></i>
										<div slot="content" style="max-width:300px;">
											EMPLACAMENTO <br>
											O valor do emplacamento e despachante é cobrado somente no 1° ano do carro e custa em média {{ props.emplacamento|money }}. No Livre, você não tem esse custo.
										</div>
									</el-tooltip>
								</label>

								<ui-money v-model="props.emplacamento" @input="carModelCalculate()"></ui-money>
							</div>

							<div class="col form-group" style="min-width:151px;">
								<label>Manutenção mensal 
									<el-tooltip placement="top">
										<i class="fas fa-question-circle"></i>
										<div slot="content" style="max-width:300px;">
											MANUTENÇÃO <br>
											O custo de manutenção são as revisões previstas em manual (a cada 10.000 KM para a maioria dos
											carros e a cada 12.000 KM para Renegade e Compass) feitas na concessionária somado a troca dos
											pneus a cada 40.000 KM. No Livre, você não se preocupa com as despesas de manutenção preventiva.
										</div>
									</el-tooltip>
								</label>

								<ui-money v-model="carModel.maintenanceMonthly" @input="carModelCalculatePeriodoMonths()"></ui-money>
								
								<small class="d-block">
									{{ carModel.maintenance|money }} nos {{ carModel.periodMonths }} meses
									<el-tooltip placement="top">
										<div slot="content" v-html="carModel.maintenanceDescription"></div>
										<i class="fas fa-question-circle"></i>
									</el-tooltip>
								</small>
							</div>
						</div>
						
						<div class="py-4"></div>
						
						<!-- Financiamento -->
						<h1 class="samydana-calc-title mb-3">Financiamento</h1>
						<div class="row align-items-baseline">
							<div class="col-12 col-md-3 form-group">
								<label>Entrada 
									<el-tooltip placement="top">
										<i class="fas fa-question-circle"></i>
										<div slot="content" style="max-width:300px;">
											VALOR TOTAL DE ENTRADA <br>
											Consideramos a entrada do financiamento de 30% do preço ofertado pela montadora. Na compra do carro à vista e no Unidas Livre, não há valor de entrada.
										</div>
									</el-tooltip>
								</label>

								<div class="input-group form-control p-0">
									<input type="number" class="form-control border-0 bg-transparent" v-model="props.prazo_entrada_percentagem">
									<div class="input-group-append"><div class="input-group-text p-1 px-3 border-0">%</div></div>
								</div>

								<small class="d-block">{{ compResult.entrada|money }}</small>
							</div>
							<div class="col-12 col-md-3 form-group">
								<label>Taxa a.m.</label>

								<div class="input-group form-control p-0">
									<input type="number" class="form-control border-0 bg-transparent" v-model="props.prazo_juros" @input="carModelCalculate()" step="0.3">
									<div class="input-group-append"><div class="input-group-text p-1 px-3 border-0">%</div></div>
								</div>
							</div>
						</div>

						<a href="javascript:;" @click="scrollTo($refs.resultado)" class="font-weight-bold">Ver resultado <svg width="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M7.0051 1.20874V12.3787L2.1251 7.49874C1.7351 7.10874 1.0951 7.10874 0.705098 7.49874C0.315098 7.88874 0.315098 8.51874 0.705098 8.90874L7.2951 15.4987C7.6851 15.8887 8.3151 15.8887 8.7051 15.4987L15.2951 8.90874C15.6851 8.51874 15.6851 7.88874 15.2951 7.49874C15.1083 7.31149 14.8546 7.20625 14.5901 7.20625C14.3256 7.20625 14.0719 7.31149 13.8851 7.49874L9.0051 12.3787V1.20874C9.0051 0.65874 8.5551 0.20874 8.0051 0.20874C7.4551 0.20874 7.0051 0.65874 7.0051 1.20874Z" fill="#3CD5EA"/>
						</svg></a>
					</div>
				</div>


				<div class="py-5" ref="resultado" style="background:#F8FCFF;">
					<div class="container">
						<h1 class="samydana-calc-title mb-3" style="font-size: 48px;">Resultado</h1>

						<div class="py-4"></div>
						<div class="text-center mb-3 font-weight-bold">Valor corrigido ao final do período</div>
						<div v-if="compResult.chart2">
							<div class="d-md-none bg-light p-2 pattern-vlines">
								<div v-for="i in compResult.chart2.items">
									<div class="bg-blue p-2 text-white mb-2" :style="`width:${i.chartPercent}%; font-family:Exo;`">
										<div v-html="i.title"></div>
										<div>{{ i.investimentoTotal|money }}</div>
									</div>
								</div>
							</div>

							<div class="d-none d-md-block bg-light py-3 px-5 pattern-hlines">
								<div class="row align-items-end" style="position:relative; height:300px;">
									<div class="col border mx-1 bg-blue text-white text-center d-flex align-items-end" v-for="i in compResult.chart2.items" :style="`height:${i.chartPercent}%;`">
										<div class="mx-auto pb-2" style="font-family:Exo;">
											<div v-html="i.title"></div> &nbsp;
											<div>{{ i.investimentoTotal|money }}</div>
										</div>
									</div>
								</div>
							</div>
						</div>


						<div class="py-4"></div>
						<div class="row">
							<div class="col-12 col-md-4 mb-3" style="position:relative;" v-for="r in compResult.results">
								<!-- <div class="bg-success samydana-best-option" :style="{opacity:(r.best? 1: 0)}">Melhor opção</div> -->
								<div class="card">
									<div class="card-title text-primary">
										<div class="d-flex align-items-center" style="height:60px;">
											<div>
												<svg v-if="r.id=='compraVista'" width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="25" cy="25" r="23.5" stroke="#28A745" stroke-width="3"/><path d="M25.0001 12C22.4289 12 19.9155 12.7624 17.7776 14.1909C15.6398 15.6193 13.9735 17.6496 12.9896 20.0251C12.0056 22.4005 11.7482 25.0144 12.2498 27.5362C12.7514 30.0579 13.9896 32.3743 15.8076 34.1924C17.6257 36.0105 19.9421 37.2486 22.4639 37.7502C24.9857 38.2518 27.5996 37.9944 29.975 37.0104C32.3504 36.0264 34.3808 34.3601 35.8092 32.2223C37.2376 30.0844 38 27.571 38 24.9998C37.9962 21.5532 36.6254 18.2488 34.1882 15.8117C31.7511 13.3746 28.4467 12.0038 25.0001 12ZM25.4316 23.6629C29.214 24.8819 30.0082 26.7883 30.0082 28.1727C30.0082 30.1473 28.5289 31.7145 26.4015 32.1897V34.1046H23.5928V32.158C21.5136 31.6193 19.9928 29.9461 19.9928 27.9683H22.8016C22.8016 28.8014 23.8426 29.5322 25.0297 29.5322C26.0812 29.5322 27.1994 29.0557 27.1994 28.1727C27.1994 27.5724 26.1665 26.8517 24.5693 26.3367C20.7862 25.1162 19.9928 23.2106 19.9928 21.8266C19.9928 19.854 21.4689 18.2879 23.5928 17.8109V15.8951H26.4015V17.8398C28.484 18.3765 30.0081 20.0511 30.0081 22.0309H27.1994C27.1994 21.1978 26.1583 20.4671 24.9712 20.4671C23.9198 20.4671 22.8016 20.9435 22.8016 21.8266C22.8016 22.4268 23.8337 23.1476 25.4316 23.6629Z" fill="#28A745"/></svg>
												<svg v-if="r.id=='compraFinanciada'" width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="25" cy="25" r="23.5" stroke="#DC3545" stroke-width="3"/><path fill-rule="evenodd" clip-rule="evenodd" d="M13.8958 12.5C12.1516 12.5 10.7143 13.9373 10.7143 15.6815V30.5284C10.7143 32.2726 12.1516 33.7099 13.8958 33.7099H24.5007C24.548 33.7099 24.5951 33.7088 24.6419 33.7068C24.3408 33.1146 24.1203 32.4809 23.9893 31.8222C23.7076 30.4061 23.8522 28.9383 24.4047 27.6044C24.9572 26.2704 25.8929 25.1303 27.0934 24.3282C27.2846 24.2004 27.4811 24.0822 27.6822 23.9737V15.6815C27.6822 13.9373 26.2449 12.5 24.5007 12.5H13.8958ZM14.9563 16.742H23.4402C23.5807 16.74 23.7203 16.766 23.8507 16.8184C23.9811 16.8708 24.0998 16.9486 24.1999 17.0472C24.2999 17.1459 24.3794 17.2635 24.4336 17.3931C24.4879 17.5228 24.5158 17.6619 24.5158 17.8025C24.5158 17.943 24.4879 18.0822 24.4336 18.2118C24.3794 18.3415 24.2999 18.459 24.1999 18.5577C24.0998 18.6564 23.9811 18.7342 23.8507 18.7866C23.7203 18.839 23.5807 18.865 23.4402 18.863H14.9563C14.8157 18.865 14.6762 18.839 14.5458 18.7866C14.4154 18.7342 14.2967 18.6564 14.1966 18.5577C14.0965 18.459 14.0171 18.3415 13.9628 18.2118C13.9086 18.0822 13.8807 17.943 13.8807 17.8025C13.8807 17.6619 13.9086 17.5228 13.9628 17.3931C14.0171 17.2635 14.0965 17.1459 14.1966 17.0472C14.2967 16.9486 14.4154 16.8708 14.5458 16.8184C14.6762 16.766 14.8157 16.74 14.9563 16.742ZM16.0168 20.984C16.298 20.984 16.5678 21.0958 16.7666 21.2947C16.9655 21.4935 17.0773 21.7632 17.0773 22.0445C17.0773 22.3257 16.9655 22.5955 16.7666 22.7944C16.5678 22.9933 16.298 23.105 16.0168 23.105C15.7355 23.105 15.4658 22.9933 15.2669 22.7944C15.068 22.5955 14.9563 22.3257 14.9563 22.0445C14.9563 21.7632 15.068 21.4935 15.2669 21.2947C15.4658 21.0958 15.7355 20.984 16.0168 20.984ZM19.1982 20.984C19.4795 20.984 19.7492 21.0958 19.9481 21.2947C20.147 21.4935 20.2587 21.7632 20.2587 22.0445C20.2587 22.3257 20.147 22.5955 19.9481 22.7944C19.7492 22.9933 19.4795 23.105 19.1982 23.105C18.917 23.105 18.6472 22.9933 18.4484 22.7944C18.2495 22.5955 18.1377 22.3257 18.1377 22.0445C18.1377 21.7632 18.2495 21.4935 18.4484 21.2947C18.6472 21.0958 18.917 20.984 19.1982 20.984ZM22.3797 20.984C22.661 20.984 22.9307 21.0958 23.1296 21.2947C23.3285 21.4935 23.4402 21.7632 23.4402 22.0445C23.4402 22.3257 23.3285 22.5955 23.1296 22.7944C22.9307 22.9933 22.661 23.105 22.3797 23.105C22.0985 23.105 21.8287 22.9933 21.6298 22.7944C21.431 22.5955 21.3192 22.3257 21.3192 22.0445C21.3192 21.7632 21.431 21.4935 21.6298 21.2947C21.8287 21.0958 22.0985 20.984 22.3797 20.984ZM16.0168 24.1654C16.298 24.1654 16.5678 24.2773 16.7666 24.4761C16.9655 24.675 17.0773 24.9447 17.0773 25.2259C17.0773 25.5072 16.9655 25.777 16.7666 25.9759C16.5678 26.1748 16.298 26.2864 16.0168 26.2864C15.7355 26.2864 15.4658 26.1748 15.2669 25.9759C15.068 25.777 14.9563 25.5072 14.9563 25.2259C14.9563 24.9447 15.068 24.675 15.2669 24.4761C15.4658 24.2773 15.7355 24.1654 16.0168 24.1654ZM19.1982 24.1654C19.4795 24.1654 19.7492 24.2773 19.9481 24.4761C20.147 24.675 20.2587 24.9447 20.2587 25.2259C20.2587 25.5072 20.147 25.777 19.9481 25.9759C19.7492 26.1748 19.4795 26.2864 19.1982 26.2864C18.917 26.2864 18.6472 26.1748 18.4484 25.9759C18.2495 25.777 18.1377 25.5072 18.1377 25.2259C18.1377 24.9447 18.2495 24.675 18.4484 24.4761C18.6472 24.2773 18.917 24.1654 19.1982 24.1654ZM22.3797 24.1654C22.661 24.1654 22.9307 24.2773 23.1296 24.4761C23.3285 24.675 23.4402 24.9447 23.4402 25.2259C23.4402 25.5072 23.3285 25.777 23.1296 25.9759C22.9307 26.1748 22.661 26.2864 22.3797 26.2864C22.0985 26.2864 21.8287 26.1748 21.6298 25.9759C21.431 25.777 21.3192 25.5072 21.3192 25.2259C21.3192 24.9447 21.431 24.675 21.6298 24.4761C21.8287 24.2773 22.0985 24.1654 22.3797 24.1654ZM16.0168 27.3469C16.298 27.3469 16.5678 27.4587 16.7666 27.6576C16.9655 27.8565 17.0773 28.1262 17.0773 28.4074C17.0773 28.6887 16.9655 28.9585 16.7666 29.1574C16.5678 29.3562 16.298 29.4679 16.0168 29.4679C15.7355 29.4679 15.4658 29.3562 15.2669 29.1574C15.068 28.9585 14.9563 28.6887 14.9563 28.4074C14.9563 28.1262 15.068 27.8565 15.2669 27.6576C15.4658 27.4587 15.7355 27.3469 16.0168 27.3469ZM19.1982 27.3469C19.4795 27.3469 19.7492 27.4587 19.9481 27.6576C20.147 27.8565 20.2587 28.1262 20.2587 28.4074C20.2587 28.6887 20.147 28.9585 19.9481 29.1574C19.7492 29.3562 19.4795 29.4679 19.1982 29.4679C18.917 29.4679 18.6472 29.3562 18.4484 29.1574C18.2495 28.9585 18.1377 28.6887 18.1377 28.4074C18.1377 28.1262 18.2495 27.8565 18.4484 27.6576C18.6472 27.4587 18.917 27.3469 19.1982 27.3469ZM22.3797 27.3469C22.661 27.3469 22.9307 27.4587 23.1296 27.6576C23.3285 27.8565 23.4402 28.1262 23.4402 28.4074C23.4402 28.6887 23.3285 28.9585 23.1296 29.1574C22.9307 29.3562 22.661 29.4679 22.3797 29.4679C22.0985 29.4679 21.8287 29.3562 21.6298 29.1574C21.431 28.9585 21.3192 28.6887 21.3192 28.4074C21.3192 28.1262 21.431 27.8565 21.6298 27.6576C21.8287 27.4587 22.0985 27.3469 22.3797 27.3469ZM28.2648 25.5481C29.3355 24.8326 30.5943 24.4508 31.8821 24.4508C33.6083 24.4527 35.2633 25.1392 36.4839 26.3599C37.7045 27.5805 38.3911 29.2354 38.393 30.9616C38.393 32.2494 38.0111 33.5082 37.2957 34.579C36.5803 35.6497 35.5634 36.4842 34.3737 36.977C33.184 37.4699 31.8748 37.5988 30.6118 37.3476C29.3488 37.0964 28.1887 36.4763 27.2781 35.5657C26.3675 34.6551 25.7474 33.495 25.4962 32.232C25.245 30.969 25.3739 29.6598 25.8667 28.4701C26.3595 27.2804 27.194 26.2635 28.2648 25.5481ZM34.3903 32.5507C34.3903 31.8574 33.9926 30.9026 32.0982 30.2921C31.2979 30.034 30.7809 29.673 30.7809 29.3724C30.7809 28.9301 31.341 28.6915 31.8676 28.6915C32.4622 28.6915 32.9836 29.0574 32.9836 29.4747H34.3903C34.3903 28.4831 33.627 27.6444 32.584 27.3756V26.4016H31.1772V27.3611C30.1135 27.6 29.3742 28.3844 29.3742 29.3724C29.3742 30.0655 29.7716 31.0199 31.6663 31.6312C32.4662 31.8891 32.9836 32.2501 32.9836 32.5507C32.9836 32.993 32.4235 33.2316 31.8969 33.2316C31.3024 33.2316 30.7809 32.8656 30.7809 32.4484H29.3742C29.3742 33.439 30.1359 34.277 31.1772 34.5468V35.5217H32.584V34.5626C33.6494 34.3246 34.3903 33.5397 34.3903 32.5507Z" fill="#DC3545"/></svg>
												<svg v-if="r.id=='aluguel'" width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="25.0001" cy="25" r="23.5" stroke="#325D94" stroke-width="3"/><path fill-rule="evenodd" clip-rule="evenodd" d="M40 22.7142C40 27.4325 36.175 31.2575 31.4567 31.2575C26.7383 31.2575 22.9134 27.4325 22.9134 22.7142C22.9134 17.9958 26.7383 14.1708 31.4567 14.1708C36.175 14.1708 40 17.9958 40 22.7142ZM15.4805 21.5998H20.3132V22.8934C20.3132 23.1645 20.3235 23.4304 20.3431 23.6909H15.4805C15.0792 23.6909 14.718 23.9276 14.5976 24.3222L13.6746 27.1629H21.2313C21.7095 28.2233 22.3327 29.147 22.9977 29.9356L22.5433 30.0036C22.3025 30.043 22.1019 30.2403 22.0618 30.477L21.9414 31.1083C21.8611 31.4634 22.1822 31.8185 22.5433 31.8185H24.5097C24.6186 31.8185 24.7228 31.7867 24.8131 31.7324C26.1095 32.8132 27.2092 33.3749 27.3212 33.4309C27.3711 33.4557 27.4245 33.4725 27.4793 33.4805V35.8823C27.4793 36.3952 27.0379 36.8292 26.5162 36.8292H24.8709C24.3492 36.8292 23.9077 36.3952 23.9077 35.8823V34.4225H14.116V35.8823C14.116 36.3952 13.6746 36.8292 13.1529 36.8292H11.5076C10.9859 36.8292 10.5445 36.3952 10.5445 35.8823V31.2661C10.5445 30.3587 10.6649 29.4512 10.8655 28.5832H10.5043C9.98266 28.5832 9.54123 28.1492 9.54123 27.6363V27.084C9.54123 26.5711 9.98266 26.1371 10.5043 26.1371H11.7484L12.551 23.6909C12.9523 22.4283 14.116 21.5998 15.4805 21.5998ZM15.5206 31.8185H13.5542C13.2332 31.8185 12.9523 31.5423 12.9523 31.2267V30.3192C12.9523 29.9641 13.2733 29.688 13.6345 29.7274L15.4805 30.0036C15.7212 30.043 15.9219 30.2403 15.962 30.477L16.0824 31.1083C16.1627 31.4634 15.8818 31.8185 15.5206 31.8185ZM35.7552 18.5376C35.9357 18.3574 36.1807 18.2567 36.4354 18.2567C36.6901 18.2567 36.9351 18.3574 37.1156 18.5377C37.4949 18.9135 37.4946 19.5167 37.1148 19.8922L30.7813 26.1485C30.4035 26.5219 29.7941 26.5219 29.4165 26.1485L26.9129 23.6732C26.5331 23.2976 26.5328 22.6945 26.9121 22.3186C27.0926 22.1384 27.3376 22.0377 27.5923 22.0377C27.847 22.0377 28.092 22.1383 28.2725 22.3185L30.1018 24.1273L35.7543 18.5385L35.7552 18.5376Z" fill="#325D94"/></svg>
											</div>
											<div class="pl-2"><h4 class="card-title-text text-uppercase text-primary">{{ r.title }}</h4></div>
										</div>
									</div>

									<h5 class="card-title text-primary">
										{{ r.investimentoTotal|money }}*
										<el-tooltip placement="top">
											<div slot="content" v-html="r.investimentoTotalTooltip"></div>
											<i class="fas fa-question-circle"></i>
										</el-tooltip>
									</h5>
									<small class="card-title d-block text-muted" v-html="r.investimentoTotalInfo"></small>
									<div class="samydana-calc-code mb-2" v-if="test" v-html="r.investimentoTotalCode"></div>

									<!-- Informações detalhadas -->
									<div class="card-body p-0 pt-3">
										<div v-for="i in r.items">
											<div>
												<div class="d-inline-block mr-1" v-html="i.text"></div>
												<el-tooltip placement="top" v-if="i.tooltip">
													<div slot="content" v-html="i.tooltip" style="max-width:300px;"></div>
													<i class="fas fa-question-circle"></i>
												</el-tooltip>
											</div>
											<small class="d-block text-muted" v-if="i.obs" v-html="i.obs"></small>
											<div class="samydana-calc-code" v-if="test && i.code" v-html="i.code"></div>
											<br>
										</div>

										<div v-if="r.id=='aluguel'">
											<div class="d-flex align-items-center mb-4">
												<div style="width:50px; text-align:center;"><svg width="80%" viewBox="0 0 24 32" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M22.3806 5.7788H20.8354L19.8384 2.66327C19.34 1.05526 17.8446 0 16.1997 0H7.37711C5.68237 0 4.23685 1.05526 3.7384 2.66327L2.74149 5.7788H1.19629C0.548298 5.7788 0 6.33155 0 6.98481V7.68831C0 8.34157 0.548298 8.89433 1.19629 8.89433H1.6449C1.39567 9.99984 1.24613 11.1556 1.24613 12.3114V18.1907C1.24613 18.8439 1.79443 19.3967 2.44242 19.3967H4.48608C5.13407 19.3967 5.68237 18.8439 5.68237 18.1907V16.3314H17.8446V18.1907C17.8446 18.8439 18.3929 19.3967 19.0409 19.3967H21.0846C21.7326 19.3967 22.2809 18.8439 22.2809 18.1907V12.3616C22.2809 11.2058 22.1313 10.0501 21.8821 8.94458H22.3307C22.9787 8.94458 23.527 8.39182 23.527 7.73857V7.03506C23.5769 6.33155 23.0286 5.7788 22.3806 5.7788ZM6.28051 3.46728C6.43005 2.96478 6.87866 2.66327 7.37711 2.66327H16.1997C16.6982 2.66327 17.1468 2.96478 17.2963 3.46728L18.4428 7.08531H5.13407L6.28051 3.46728ZM7.42696 13.0149H4.98454C4.58577 13.0149 4.23686 12.6631 4.23686 12.2611V11.1053C4.23686 10.6531 4.63562 10.3013 5.08423 10.3516L7.37711 10.7033C7.67618 10.7536 7.92541 11.0048 7.97525 11.3063L8.12479 12.1104C8.22448 12.5626 7.87556 13.0149 7.42696 13.0149ZM19.34 12.2611C19.34 12.6631 18.9911 13.0149 18.5923 13.0149H16.1499C15.7013 13.0149 15.3025 12.5626 15.4022 12.1104L15.5518 11.3063C15.6016 11.0048 15.8508 10.7536 16.1499 10.7033L18.4428 10.3516C18.8914 10.3013 19.2902 10.6531 19.2902 11.1053V12.2611H19.34Z" fill="#005B9E"/>
													<rect y="21.8536" width="24" height="3.12194" rx="1" fill="#005B9E"/>
													<rect x="10.8387" y="23.4147" width="3.09678" height="8.58534" rx="1" fill="#005B9E"/>
												</svg></div>

												<div class="flex-grow-1 pl-3">Manutenções preventivas <br>de custo zero	</div>
											</div>

											<div class="d-flex align-items-center mb-4">
												<div style="width:50px; text-align:center;"><svg width="80%" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M8.49244 16.3102C9.02475 15.7813 9.02475 14.9159 8.49244 14.3837L5.02746 10.9192C4.50318 10.395 3.64588 10.3809 3.10064 10.9192L2.52258 11.4972L7.91442 16.8882L8.49244 16.3102Z" fill="#005B9E"/>
													<path d="M0.191193 15.04C-0.60566 18.4326 1.08276 22.2236 5.36097 26.6449C9.08396 30.2498 12.3301 32 15.2792 32C15.7364 32 16.1903 31.9575 16.6345 31.8759C17.8135 31.6539 18.9108 31.0628 19.8056 30.1649L20.0407 29.933L14.6489 24.542C14.1728 24.8844 13.2246 26.5786 11.6672 25.9298C9.64241 25.0775 6.91873 22.3575 6.06637 20.3298C5.41784 18.772 7.05902 17.892 7.45434 17.3485L2.06251 11.9575C1.95179 12.0968 0.627691 13.1763 0.191193 15.04Z" fill="#005B9E"/>
													<path d="M15.6874 23.5037L15.1094 24.0817L20.5012 29.4727L21.0792 28.8947C21.6083 28.3625 21.6083 27.5005 21.0792 26.9682L17.6142 23.5037C17.3562 23.2457 17.0133 23.1053 16.6508 23.1053C16.2851 23.1054 15.9454 23.2458 15.6874 23.5037Z" fill="#005B9E"/>
													<path d="M16.6963 0C9.85768 0 3.91069 4.529 2.01978 10.9649L2.02631 10.9551L2.08182 11.0171C2.32715 10.8604 3.23632 9.37064 4.80549 10.0114C4.92304 9.75018 5.04716 9.49551 5.17778 9.24407L7.0752 10.3379L7.40178 9.77304L5.49783 8.67264C6.55922 6.88327 8.02881 5.39427 9.75317 4.3004L10.8472 6.19427L11.4122 5.86774L10.3149 3.96734C12.0817 2.9714 14.0836 2.3771 16.1835 2.29547V4.48977H16.8367V2.289C18.9725 2.31187 20.9842 2.85064 22.7543 3.78777L21.6602 5.68167L22.2252 6.0082L23.3258 4.10454C25.0959 5.15924 26.5981 6.61884 27.6987 8.35924L25.8078 9.44987L26.1344 10.018L28.0351 8.91764C29.0213 10.6678 29.619 12.6629 29.7006 14.7886H27.5126V15.4417H29.7104C29.6875 17.5478 29.1552 19.5658 28.2114 21.3552L26.3173 20.2646L25.9907 20.8295L27.8914 21.9267C26.8496 23.6867 25.3963 25.1952 23.6426 26.3054L22.5485 24.4083L21.9836 24.7348L23.0809 26.6418C22.7282 26.841 22.3624 27.0238 21.9868 27.1904C22.4273 28.3142 21.7917 29.3725 20.8797 30.0214C20.8797 30.0214 20.8797 30.0246 20.883 30.0214C27.3983 28.183 31.9998 22.1912 31.9998 15.3013C31.9998 6.86371 25.1351 0 16.6963 0Z" fill="#005B9E"/>
													<path d="M16.1683 6.39746H15.5153V7.05053H16.1683V6.39746Z" fill="#005B9E"/>
													<path d="M17.4746 6.39746H16.8215V7.05053H17.4746V6.39746Z" fill="#005B9E"/>
													<path d="M14.8621 6.39746H14.209V7.05053H14.8621V6.39746Z" fill="#005B9E"/>
													<path d="M19.3661 20.2201H18.713V20.8732H19.3661V20.2201Z" fill="#005B9E"/>
													<path d="M21.9786 20.2201H21.3256V20.8732H21.9786V20.2201Z" fill="#005B9E"/>
													<path d="M20.6724 20.2201H20.0193V20.8732H20.6724V20.2201Z" fill="#005B9E"/>
													<path d="M12.6749 16.0664L11.9027 16.8162V17.4205H16.5468V16.6035H13.2791V16.5812L13.8611 16.044C15.3943 14.5668 16.3678 13.4813 16.3678 12.1384C16.3678 11.0977 15.7076 10.0234 14.1409 10.0234C13.3016 10.0234 12.5854 10.3367 12.0818 10.762L12.3952 11.4558C12.7309 11.176 13.2792 10.8404 13.9396 10.8404C15.025 10.8404 15.3831 11.523 15.3831 12.2616C15.3719 13.3583 14.5326 14.2983 12.6749 16.0664Z" fill="#005B9E"/>
													<path d="M20.5545 10.1464L17.3088 14.7905V15.4396H20.6888V17.4203H21.6178V15.4396H22.6363V14.6674H21.6178V10.1464H20.5545ZM20.6888 12.239V14.6674H18.3049V14.6449L20.0845 12.1719C20.286 11.8361 20.4651 11.5228 20.6888 11.0976H20.7224C20.7001 11.478 20.6888 11.8586 20.6888 12.239Z" fill="#005B9E"/>
												</svg></div>

												<div class="flex-grow-1 pl-3">Assistência 24h inclusa</div>
											</div>

											<div class="d-flex align-items-center mb-4">
												<div style="width:50px; text-align:center;"><svg width="80%" viewBox="0 0 42 32" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M27.6861 5.65477C27.6861 5.4356 27.5109 5.26025 27.2919 5.26025H18.3552C18.1362 5.26025 17.9609 5.4356 17.9609 5.65477V7.23286C17.9609 7.45203 18.1362 7.62738 18.3552 7.62738H27.2919C27.5109 7.62738 27.6861 7.45203 27.6861 7.23286V5.65477Z" fill="#005B9E"/>
													<path d="M27.6861 12.1425V10.5644C27.6861 10.3453 27.5109 10.1699 27.2919 10.1699H18.3552C18.1362 10.1699 17.9609 10.3453 17.9609 10.5644V12.1425C17.9609 12.3617 18.1362 12.537 18.3552 12.537H27.2919C27.5109 12.537 27.6861 12.3617 27.6861 12.1425Z" fill="#005B9E"/>
													<path d="M17.9609 15.5178V17.0959C17.9609 17.3151 18.1362 17.4904 18.3552 17.4904H21.8598L22.0788 16.789C22.254 16.1753 22.5607 15.6055 22.955 15.1233H18.3552C18.1362 15.1233 17.9609 15.2548 17.9609 15.5178Z" fill="#005B9E"/>
													<path d="M18.7056 21.2164C18.7056 20.9096 18.7495 20.6466 18.8371 20.3397H2.32178V2.36712H30.665V12.9315H33.0306V1.18356C33.0306 0.526028 32.5049 0 31.8478 0H1.18279C0.525686 0 0 0.526028 0 1.18356V21.4794C0 22.137 0.525686 22.663 1.18279 22.663H18.8809C18.7933 22.4 18.7495 22.0931 18.7495 21.7425V21.2164H18.7056Z" fill="#005B9E"/>
													<path d="M8.29584 14.4289L5.91441 12.046C5.78863 11.9185 5.6171 11.8468 5.43812 11.8468C5.25914 11.8468 5.08761 11.9185 4.96184 12.046C4.69648 12.3115 4.69648 12.7336 4.96184 12.9992L7.81275 15.8519C8.07811 16.1175 8.50677 16.1175 8.77213 15.8519L15.9845 8.64171C16.2498 8.37618 16.2498 7.95405 15.9845 7.68852C15.8587 7.5611 15.6872 7.48938 15.5082 7.48938C15.3292 7.48938 15.1577 7.5611 15.0319 7.68852L8.29584 14.4289Z" fill="#005B9E" stroke="#005B9E" stroke-width="2"/>
													<path d="M40.5216 20.1206H39.1636L38.2874 17.4028C37.8493 16 36.5351 15.0795 35.0895 15.0795H27.3356C25.8462 15.0795 24.5758 16 24.1377 17.4028L23.2616 20.1206H21.9035C21.3341 20.1206 20.8522 20.6028 20.8522 21.1726V21.7863C20.8522 22.3562 21.3341 22.8384 21.9035 22.8384H22.2978C22.0788 23.8028 21.9474 24.811 21.9474 25.8192V30.948C21.9474 31.5178 22.4292 32 22.9987 32H24.7948C25.3643 32 25.8462 31.5178 25.8462 30.948V29.326H36.5351V30.948C36.5351 31.5178 37.017 32 37.5865 32H39.3826C39.9521 32 40.434 31.5178 40.434 30.948V25.863C40.434 24.8548 40.3025 23.8466 40.0835 22.8822H40.4778C41.0473 22.8822 41.5291 22.4 41.5291 21.8302V21.2165C41.5729 20.6028 41.0911 20.1206 40.5216 20.1206ZM26.3719 18.1041C26.5033 17.6658 26.8976 17.4028 27.3356 17.4028H35.0895C35.5276 17.4028 35.9218 17.6658 36.0532 18.1041L37.0608 21.2603H25.3643L26.3719 18.1041ZM27.3794 26.4329H25.2329C24.8824 26.4329 24.5758 26.126 24.5758 25.7754V24.7671C24.5758 24.3726 24.9262 24.0658 25.3205 24.1096L27.3356 24.4165C27.5985 24.4603 27.8175 24.6795 27.8613 24.9425L27.9927 25.6439C28.0803 26.0384 27.7737 26.4329 27.3794 26.4329ZM37.8493 25.7754C37.8493 26.126 37.5427 26.4329 37.1922 26.4329H35.0457C34.6514 26.4329 34.301 26.0384 34.3886 25.6439L34.52 24.9425C34.5638 24.6795 34.7828 24.4603 35.0457 24.4165L37.0608 24.1096C37.4551 24.0658 37.8055 24.3726 37.8055 24.7671V25.7754H37.8493Z" fill="#005B9E"/>
												</svg></div>

												<div class="flex-grow-1 pl-3">Sem gastos com documentação <br>e despachante</div>
											</div>

											<div class="d-flex align-items-center mb-4">
												<div style="width:50px; text-align:center;"><svg width="80%" viewBox="0 0 36 32" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M34.4127 2.67735C31.6444 2.47465 28.9714 1.59058 26.6373 0.105703C26.5331 0.0367884 26.4105 0 26.2851 0C26.1597 0 26.0372 0.0367884 25.933 0.105703C23.5994 1.59068 20.9267 2.47477 18.1587 2.67735C18.0753 2.67731 17.9928 2.6935 17.9157 2.72502C17.8386 2.75654 17.7686 2.80275 17.7097 2.86102C17.6507 2.9193 17.6039 2.98848 17.572 3.06463C17.5401 3.14077 17.5238 3.22239 17.5238 3.3048V8.65886C17.5238 16.9823 25.6551 21.0962 26.0011 21.2672C26.0893 21.3107 26.1865 21.3333 26.2851 21.3333C26.3837 21.3333 26.4809 21.3107 26.5691 21.2672C26.9151 21.0962 35.0476 16.9823 35.0476 8.65886V3.3048C35.0477 3.22239 35.0313 3.14077 34.9994 3.06463C34.9675 2.98848 34.9207 2.9193 34.8617 2.86102C34.8028 2.80275 34.7328 2.75654 34.6557 2.72502C34.5787 2.6935 34.4961 2.67731 34.4127 2.67735ZM31.0223 7.98611L25.6043 13.3402C25.5159 13.4276 25.4109 13.497 25.2954 13.5443C25.1798 13.5916 25.056 13.6159 24.9309 13.6159C24.8059 13.6159 24.682 13.5916 24.5665 13.5443C24.4509 13.497 24.346 13.4276 24.2576 13.3402L21.5491 10.6632C21.4604 10.5758 21.39 10.4721 21.3419 10.3578C21.2938 10.2436 21.269 10.1211 21.2689 9.99734C21.2688 9.8736 21.2933 9.75106 21.3412 9.63672C21.3891 9.52238 21.4593 9.41848 21.5478 9.33099C21.6364 9.24349 21.7415 9.17411 21.8572 9.12682C21.9729 9.07952 22.0969 9.05524 22.2221 9.05536C22.3473 9.05548 22.4713 9.08001 22.5869 9.12753C22.7025 9.17505 22.8075 9.24463 22.8959 9.3323L24.9309 11.3439L29.6755 6.65521C29.7639 6.56783 29.8689 6.4985 29.9844 6.45121C30.1 6.40391 30.2238 6.37957 30.3489 6.37957C30.4739 6.37957 30.5978 6.40391 30.7133 6.4512C30.8289 6.4985 30.9338 6.56781 31.0223 6.6552C31.1107 6.74259 31.1809 6.84633 31.2287 6.96051C31.2766 7.07469 31.3012 7.19707 31.3012 7.32065C31.3012 7.44424 31.2766 7.56662 31.2287 7.68079C31.1809 7.79497 31.1107 7.89872 31.0223 7.98611Z" fill="#005B9E"/>
													<path d="M27.0083 21.9226C26.7498 22.0511 26.4649 22.118 26.1759 22.118C25.887 22.118 25.6021 22.0511 25.3436 21.9226C22.9164 20.6177 20.8025 18.8039 19.1477 16.6064H6.02492L8.5034 10.6933C8.5781 10.5143 8.70441 10.3615 8.86636 10.254C9.02832 10.1465 9.21863 10.0893 9.41325 10.0895H16.4831C16.4124 9.53135 16.3766 8.96933 16.3759 8.40673V7.61902H9.41325C8.72893 7.61789 8.05965 7.81889 7.49009 8.1966C6.92054 8.57431 6.47631 9.11175 6.21361 9.74091L4.41434 14.0339C4.11303 13.8368 3.7611 13.7302 3.40059 13.7269H1.89078C1.64248 13.7269 1.39661 13.7756 1.16721 13.8702C0.93781 13.9648 0.729372 14.1035 0.553797 14.2783C0.378221 14.4531 0.238948 14.6607 0.143927 14.8891C0.0489066 15.1175 0 15.3623 0 15.6095C0 15.8568 0.0489066 16.1016 0.143927 16.33C0.238948 16.5584 0.378221 16.7659 0.553797 16.9407C0.729372 17.1156 0.93781 17.2542 1.16721 17.3488C1.39661 17.4434 1.64248 17.4921 1.89078 17.4921H2.79279C2.47333 17.7904 2.21857 18.1506 2.0442 18.5506C1.86983 18.9506 1.77954 19.3819 1.77889 19.8179V30.5605C1.77954 30.9423 1.93221 31.3082 2.20342 31.578C2.47464 31.8479 2.84226 31.9996 3.22568 32H6.78409C7.16751 31.9996 7.53513 31.8479 7.80635 31.578C8.07757 31.3082 8.23024 30.9423 8.23089 30.5605V28.8993H24.0242V30.5605C24.0248 30.9423 24.1775 31.3082 24.4487 31.578C24.7199 31.8479 25.0876 31.9996 25.471 32H29.0294C29.4128 31.9996 29.7804 31.8479 30.0517 31.578C30.3229 31.3082 30.4755 30.9423 30.4762 30.5605V19.8179C30.4762 19.7253 30.4553 19.6387 30.4475 19.5483C29.3943 20.4658 28.2406 21.2623 27.0083 21.9226ZM6.89593 23.9157C6.45597 23.9157 6.02589 23.7858 5.66008 23.5424C5.29427 23.299 5.00916 22.9531 4.8408 22.5484C4.67245 22.1436 4.6284 21.6983 4.71425 21.2687C4.80009 20.839 5.01196 20.4444 5.32307 20.1346C5.63417 19.8249 6.03054 19.6139 6.46205 19.5285C6.89357 19.443 7.34084 19.4869 7.74731 19.6545C8.15378 19.8222 8.5012 20.106 8.74563 20.4703C8.99006 20.8345 9.12052 21.2627 9.12052 21.7008C9.11979 22.288 8.88518 22.851 8.46814 23.2662C8.05111 23.6814 7.4857 23.915 6.89593 23.9157ZM20.5766 26.3361H11.6785C11.531 26.3361 11.3896 26.2778 11.2853 26.1739C11.181 26.0701 11.1224 25.9293 11.1224 25.7824C11.1224 25.6355 11.181 25.4947 11.2853 25.3909C11.3896 25.287 11.531 25.2287 11.6785 25.2287H20.5766C20.7241 25.2287 20.8655 25.287 20.9698 25.3909C21.0741 25.4947 21.1327 25.6355 21.1327 25.7824C21.1327 25.9293 21.0741 26.0701 20.9698 26.1739C20.8655 26.2778 20.7241 26.3361 20.5766 26.3361Z" fill="#005B9E"/>
												</svg></div>

												<div class="flex-grow-1 pl-3">Sem entrada e mensalidades <br>fixas sem juros</div>
											</div>

											<br>
											<a href="http://livre.unidas.com.br/" target="_blank" class="btn btn-yellow-custom btn-yellow btn-block text-uppercase"
                                                style="border-radius:50px!important;"
                                            >Assinar agora</a>

                                            <br>
                                            <div>
                                                Assine agora, utilizando o cupom de 5% <strong>de desconto</strong>
                                                <div class="border border-primary text-center text-primary font-weight-bold p-3 mt-3"
                                                    style="border-radius:25px;" @click="$copy('SAMY5OFF').then(() => { $swal('SAMY5OFF', 'Código copiado'); })"
                                                >SAMY5OFF</div>
                                            </div>
										</div>
									</div>
								</div>
							</div>
						</div>


						<div class="mt-5 samydana-calc-footer-obs">
							<div><strong>* Preço corrigido com a curva de juros com base na ANBIMA.</strong></div>
							<div style="white-space:line-pre;">Base para cálculo: &nbsp;
							beta1: {{ anbima.beta1|toFixed(2) }}  |
							beta2: {{ anbima.beta2|toFixed(2) }}  |
							beta3: {{ anbima.beta3|toFixed(2) }}  |
							beta4: {{ anbima.beta4|toFixed(2) }}  |
							lambda1: {{ anbima.lambda1|toFixed(2) }}  |
							lambda2: {{ anbima.lambda2|toFixed(2) }}
							</div>

							<div class="mt-3">* Para os cenários de Compra a Vista e Compra Parcela foi
							considerado um valor residual de venda do carro {{ compResult.valorResidual|money }}</div>
						</div>

						<div class="row no-gutters" style="background: linear-gradient(to right, #325D94 50%, #5f82b1 100%); margin-top:100px;">
							<div class="col-12 col-lg-7 p-4">
								<div class="mb-3" style="font-style: italic; font-weight: bold; font-size: 34px; line-height: 42px; color: #FFDD00;">Samy Dana escolheu a Unidas</div>
								<div class="mb-3" style="font-style: normal; font-weight: normal; font-size: 18px; line-height: 26px; color: #FFFFFF;">
									Quando decidi trocar de carro, fiz as contas para entender como um carro zero km poderia pesar menos no meu bolso.
									O carro por assinatura foi uma grata surpresa, porque além do meu custo ser muito menor, essa modalidade também me
									poupa da burocracia e das dores de cabeça de ter, comprar ou vender um carro. A minha escolha foi o Unidas Livre,
									que tem todas as vantagens do carro por assinatura e a confiança de uma grande empresa que já utilizo os serviços
									há anos. Faça as contas e conheça mais sobre o carro por assinatura!
								</div>

								<div class="row no-gutters align-items-center">
									<div class="col-12 col-md-7 col-lg-9">
										<div style="font-style: italic; font-weight: bold; font-size: 28px; line-height: 42px; color: #FFFFFF;">Faça como Samy, venha ser livre!</div>
									</div>
									<div class="col-12 col-md-5 col-lg-3 pl-md-2 pt-3 pt-md-0">
										<a href="http://livre.unidas.com.br/" target="_blank" class="btn btn-yellow-custom btn-yellow btn-block" style="padding:10px 0px!important;">Assinar agora</a>
									</div>
								</div>
							</div>

							<div class="col-12 col-md-5 d-none d-lg-flex" style="position:relative;">
								<img src="/assets/layout/img-samy-dana-escolheu-unidas.png" alt="" style="position:absolute; width:140%; bottom:-6.5%; right:-18%;">
							</div>
						</div>
					</div>
				</div>
			</div>
		</transition>
	</form>

	<div class="bg-white border" style="position:fixed; bottom:0px; left:0px; width:100%; z-index:9;" v-if="test">
		<div class="float-right" style="position:relative; z-index:2;">
			<a href="javascript:;" class="btn" @click="testConsole=!testConsole">
				<i class="fas fa-chevron-down" :class="{'fa-rotate-90':testConsole}"></i>
			</a>
		</div>
		<el-tabs value="table" :class="{'el-tabs-pane-hidden':!testConsole}" @tab-click="testConsole=true">
			<el-tab-pane label="Tabela" name="table">
				<div style="overflow:auto; max-height:300px;">
					<table class="table table-sm table-fixed-header table-test table-borderless m-0" v-if="compResult.calculationMonths[0]">
						<tbody>
							<template v-for="(ks, ksi) in compResult.calculationMonths">
								<tr v-if="ksi==0">
									<th v-for="(k, ki) in ks" :class="`col-${ki}`">{{ ki }}</th>
								</tr>
								<tr>
									<template v-for="(k, ki) in ks">
										<td :class="`col-${ki}`" v-if="['mes', 'year', 'tempoInvestimento'].indexOf(ki)>=0">{{ k }}</td>
										<td :class="`col-${ki}`" v-else-if="['rentabilidade'].indexOf(ki)>=0">{{ (k*100)|toFixed(2) }}%</td>
										<td :class="`col-${ki}`" v-else>{{ k|money }}</td>
									</template>
								</tr>
							</template>
						</tbody>
					</table>
				</div>
			</el-tab-pane>

			<el-tab-pane label="Curva de Juros" name="second">
				<table class="table table-sm table-fixed-header table-test table-borderless m-0 mb-2">
					<tbody>
						<tr><td v-for="(c, i) in compResult.anbima">{{ i }}</td></tr>
						<tr><td v-for="(c, i) in compResult.anbima"><input type="text" class="form-control" :value="c|money('', 4)"></td></tr>
					</tbody>
				</table>

				<div style="overflow:auto; max-height:300px;">
					<table class="table table-sm table-fixed-header table-test table-borderless m-0" v-if="compResult.curvaJuros[0]">
						<thead>
							<tr>
								<th>mes</th>
								<th>taxaBrutaAno</th>
								<th>taxaBrutaPeriodo</th>
								<th>taxaLiquidaPeriodo</th>
								<th>imposto</th>
							</tr>
						</thead>
						<tbody>
							<template v-for="(ks, ksi) in compResult.curvaJuros">
								<tr>
									<td>{{ ks.mes }}</td>
									<td>{{ (ks.taxaBrutaAno*100)|money('', 2) }}%</td>
									<td>{{ (ks.taxaBrutaPeriodo*100)|money('', 2) }}%</td>
									<td>{{ (ks.taxaLiquidaPeriodo*100)|money('', 2) }}%</td>
									<td>{{ ks.imposto }}</td>
								</tr>
							</template>
						</tbody>
					</table>
				</div>
			</el-tab-pane>

			<el-tab-pane label="Resultados" name="third">
				<div class="row no-gutters">
					<div class="col-12">
						<table class="table table-sm table-fixed-header table-test table-borderless m-0" v-if="compResult.calculationMonths[0]">
							<colgroup><col width="200px"><col width="*"></colgroup>
							<tbody>
								<tr>
									<td>Valor inicial:</td>
									<td>{{ compResult.valorInicial|money }}</td>
									<td class="samydana-calc-code" v-html="compResult.valorInicialCode"></td>
								</tr>
								<tr>
									<td>Valor final:</td>
									<td>{{ compResult.valorFinal|money }}</td>
									<td class="samydana-calc-code" v-html="compResult.valorFinalCode"></td>
								</tr>
								<tr>
									<td>Depreciação anual:</td>
									<td>{{ compResult.depreciacaoAnual*100|toFixed(2) }}%</td>
									<td class="samydana-calc-code" v-html="compResult.depreciacaoAnualCode"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</el-tab-pane>

			<el-tab-pane label="Ações" name="actions" class="px-2">
				<button type="button" class="btn btn-primary" @click="cacheClear()">
					Limpar cache
				</button>
			</el-tab-pane>
		</el-tabs>
	</div>
</div></template>


<script>
import axios from 'axios';

export default {
	props: {
		prazo_entrada_percentagem: {default: 30},
		prazo_juros: {default: 1.3},
		depreciacao: {default: 1.84234701262},
		emplacamento: {default: 450},
		seguro: {default: 4},
		ipva: {default: 4},
		seguro_obrigatorio: {default: 150},
		maintenance: {default:0},
		ultimaParcelaPromocional: {default:8},
	},

	watch: {
		$props: {deep:true, handler(value) {
			this.props = Object.assign({}, value);
		}},
	},

	mounted() {
		if (this.test) {
			console.clear();
			console.log('MODO DE TESTE');
		}

		this.carModelsLoad();
	},

	methods: {

		/* São feitas duas requisições porque o /api/Cms/catalog não inclui o fipePrice nos resultados,
		o mesmo só retornado acessando mais detalhes dos veículos em /api/Cms/vehicleDetail */
		carModelsLoad() {
            this.carModel.itemFipePrice = 0;
			this.carModel.loading = true;

			let params = {
				period: this.carModel.periodMonths,
				franchise: this.carModel.monthKm,
			};

            this.$axios.get('/api/calculadora/catalog', {params}).then(resp => {
                this.carModel.loading = false;
                this.anbima = resp.data.anbima;
				this.veiculos = resp.data.catalog||[];

                // this.carModel.items = (resp.data.catalog||[])
				// 	// .filter(item => !!item.acf.precificacao.fipeCode)
				// 	.sort((a, b) => {
				// 		let aa = a.title.rendered.toUpperCase();
				// 		let bb = b.title.rendered.toUpperCase();
				// 		return (aa < bb) ? -1 : (aa > bb) ? 1 : 0;
				// 	});

                if (this.test) {
					this.carModel.periodMonths = 48;

					var veiculo = this.veiculos[Math.floor(Math.random() * this.veiculos.length)];
					this.carModel.itemSlug = veiculo.model.slug;
					this.carModelItemChange();
				}
            });
		},

		veiculoLoad() {
			let _money = this.$options.filters.money;
			let _toFixed = this.$options.filters.toFixed;

			let params = {
				period: this.carModel.periodMonths,
				franchise: this.carModel.monthKm,
				slug: this.carModel.itemSlug,
			};

			this.carModel.loading = true;
			this.$axios.get('/api/calculadora/simulation', {params}).then(resp => {
				this.carModel.loading = false;

				if (resp.data.price==0) {
					this.$swal('Erro', 'Desculpe, um erro inesperado ocorreu ao tentar buscar dados deste veículo na Unidas', 'error');
					return;
				}

				this.carModel.itemFipePrice = resp.data.price;
				this.carModel.itemFipeCode = resp.data.price;
				this.carModel.periodPrice = resp.data.parcelValue;
				this.carModel.periodPromo = resp.data.parcelPromo;
				this.carModel.maintenance = resp.data.maintenanceTotal;
				this.carModel.maintenanceMonthly = resp.data.maintenanceMonthly;
				this.carModel.maintenanceDescription = resp.data.maintenanceDescription;
				this.carModel.simulation = resp.data.simulation;

				this.carModel.periods = [48, 36, 24, 18, 12].map(period => {
					return {
						months: period,
						title: `${period} meses`,
					};
				});

				// Setando valor depreciacao
				this.carModel.depreciacaoPercentMes = resp.data.depreciacao.deprec_mes;
				this.carModel.depreciacaoPercentContrato = resp.data.depreciacao.deprec_contrato;
				// this.carModel.depreciacao = ((resp.data.depreciacao.deprec_mes*100) * this.carModel.periodMonths);
				this.carModel.depreciacao = (this.carModel.itemFipePrice/100*resp.data.depreciacao.deprec_mes) * this.carModel.periodMonths;
				this.carModel.depreciacaoInfo = `${resp.data.depreciacao.deprec_mes}% de ${_money(this.carModel.itemFipePrice)} ao mês x ${this.carModel.periodMonths} meses`;

				this.carModelCalculate();
			});
		},


		carModelItemChange() {
			this.veiculoLoad();







			return;

			let fipeCode = this.carModel.item.acf.precificacao.fipeCode||false;
			let itemFipePrice = this.carModel.itemFipePrice;


            let queryParam = {
                // locaviaModelCode: (this.carModel.item.id || ''),
                // locaviaModelCode: (this.carModel.item.slug || ''),
                locaviaModelCode: (this.carModel.item.acf.codigoLocavia || ''),
                // locaviaModelCode: (this.carModel.item.acf.precificacao.id || ''),

                marca: this.carModel.item.acf.informacoesModelo.marca.slug,
				codigo: (fipeCode||''),
                slug: this.carModel.item.slug,
                period: this.carModel.periodMonths,
                franchise: this.carModel.monthKm,
            };

            this.carModel.loading = true;
            this.$axios.get('/api/calculadora/vehicle-detail', {params:queryParam}).then(resp => {
                this.carModel.loading = false;

				let _money = this.$options.filters.money;
				let _toFixed = this.$options.filters.toFixed;

                this.carModel.itemFipePrice = (resp.data.simulation? resp.data.simulation.cashPayment.totalValue: 0);
				this.carModel.simulation = resp.data.simulation;

				if (this.carModel.itemFipePrice==0) {
					this.$swal('Erro', 'Valor da tabela FIPE indisponível', 'error');
					return;
				}

				// Setando valor manutenção
				this.carModel.maintenance = resp.data.manutencao.total;
				this.carModel.maintenanceMonthly = this.carModel.maintenance / this.carModel.periodMonths;
				this.carModel.maintenanceDescription = resp.data.manutencao.description;

				// Setando valor depreciacao
				this.carModel.depreciacaoPercentMes = resp.data.depreciacao.deprec_mes;
				this.carModel.depreciacaoPercentContrato = resp.data.depreciacao.deprec_contrato;
				// this.carModel.depreciacao = ((resp.data.depreciacao.deprec_mes*100) * this.carModel.periodMonths);
				this.carModel.depreciacao = (this.carModel.itemFipePrice/100*resp.data.depreciacao.deprec_mes) * this.carModel.periodMonths;
				this.carModel.depreciacaoInfo = `${resp.data.depreciacao.deprec_mes}% de ${_money(this.carModel.itemFipePrice)} ao mês x ${this.carModel.periodMonths} meses`;

                // Setando dados de veículo
                if (resp.data.vehicle.success) {
                    this.props.ultimaParcelaPromocional = (() => {
                        let lastValue, promoMonthsReturn=0;
                        resp.data.vehicle.data.acf.precificacao.parcels.forEach((value, index) => {
                            if (!lastValue) { lastValue = value; }
                            if (value != lastValue) { promoMonthsReturn = index; }
                            lastValue = value;
                        });
                        return promoMonthsReturn;
                    })();
    
                    let fipeCode = this.carModel.item.acf.precificacao.fipeCode;
                    this.carModel.item = resp.data.vehicle.data;
                    this.carModel.item.acf.precificacao.fipeCode = fipeCode;
    
                    this.carModel.periods = [48, 36, 24, 18, 12].map(period => {
                        return {
                            months: period,
                            title: `${period} meses`,
                            details: this.carModel.item.acf.precificacao.priceString,
                            price: this.carModel.item.acf.precificacao.value,
                            promo: this.carModel.item.acf.precificacao.valueWithDiscount,
                            promoMonths: this.props.ultimaParcelaPromocional,
                        };
                    });
        
                    let carModelMarca = this.carModel.item.acf.informacoesModelo.marca.slug;
                    // this.carModel.maintenance = this.maintenances[carModelMarca]||0;
                }

                else {
                    this.$swal('', `Valores não encontrados para o período de ${this.carModel.periodMonths} meses`, 'error');
                }
	
				this.carModelCalculate();
			}).catch(err => {
				this.carModel.loading = false;
				this.carModel.itemFipePrice = 0;
                // alert(`Valores não encontrados para o período de ${this.carModel.periodMonths} meses`);
			});
		},

		/* Atualiza todos os valores e cálculos.
		Executar este método novamente ao alterar qualquer parâmetro */
		carModelCalculate() {
			for(var i in this.carModel.periods) {
				let period = this.carModel.periods[i];
				if (this.carModel.periodMonths==period.months) {
					this.carModel.period = period;
					break;
				}
			}
		},


		/* O valor da manutenção mensal é gerado de acordo com o valor total / período de meses,
		ao alterar o valor da manutenção mensal, é necessário então multiplicar este valor pelo
		período de meses e depois calcular tudo novamente com o carModelCalculate() */
		carModelCalculatePeriodoMonths() {
			this.carModel.maintenance = this.carModel.maintenanceMonthly * this.carModel.periodMonths;
			this.carModelCalculate();
		},


		// juros, prestacoes, valor
		pgto(juros, prestacoes, valor, futureValue=0, type=0) {
			/*
			* type - when the payments are due:
			*        0: end of the period, e.g. end of month (default)
			*        1: beginning of period
			*/
			if (juros === 0) { return -(valor + futureValue)/prestacoes; }
			let pvif = Math.pow(1 + juros, prestacoes);
			let pmt = - juros * valor * (pvif + futureValue) / (pvif - 1);
			if (type === 1) { pmt /= (1 + juros); }
			return pmt;
		},

		getCurvaJuros(params={}) {

			params = Object.assign({
				meses: 240,
				format: "json",
			}, params);

			// Curva do arquivo
			let beta1 = this.anbima.beta1;
			let beta2 = this.anbima.beta2;
			let beta3 = this.anbima.beta3;
			let beta4 = this.anbima.beta4;
			let lambda1 = this.anbima.lambda1;
			let lambda2 = this.anbima.lambda2;

			let parcs = [];
			for(let m=1; m<=params.meses; m++) {
				let parc = {
					mes: m,
					taxaBrutaAno: 0,
					taxaBrutaPeriodo: 0,
					taxaLiquidaPeriodo: 0,
					imposto: 0,
				};

				parc.imposto = ((mes) => {
					if (mes>=0 && mes<6) return 22.5;
					if (mes>=6 && mes<12) return 20;
					if (mes>=12 && mes<24) return 17.5;
					return 15;
				})(m)/100;

				/* =beta1
				+(
					beta2*(
						1-Math.exp(
							(lambda1*-1)*parc.mes/12
						)
					)
					/(lambda1*parc.mes/12)
				)
				+(
					beta3*(
						(
							(1-Math.exp((lambda1*-1)*parc.mes/12))
							/(lambda1*parc.mes/12)
						)
						-(
							Math.exp((lambda1*-1)*parc.mes/12)
						)
					)
				)
				+(
					beta4*(
						(
							(
								1-Math.exp(-lambda2*parc.mes/12)
							)
							/(lambda2*parc.mes/12)
						)
						((
							Math.exp(-lambda2*parc.mes/12)
						)*-1)
					)
				) */
				
				parc.taxaBrutaAno = beta1
					+(beta2 * (1-Math.exp((lambda1*-1) * parc.mes/12)) / (lambda1*parc.mes/12))
					+(beta3 * (((1-Math.exp((lambda1*-1)*parc.mes/12)) / (lambda1*parc.mes/12)) -( Math.exp((lambda1*-1)*parc.mes/12) )))
					+(beta4 * (((1-Math.exp((lambda2*-1)*parc.mes/12)) / (lambda2*parc.mes/12)) -( Math.exp((lambda2*-1)*parc.mes/12) )));
				
				// parc.taxaBrutaAno = beta1
				// 	+(beta2*(1-Math.exp((lambda1*-1)*(m/12)))/(lambda1*(m/12)))
				// 	+(beta3*(((1-Math.exp((lambda1*-1)*(m/12)))/(lambda1*(m/12)))-(Math.exp((lambda1*-1)*(m/12)))))
				// 	+(beta4*(((1-Math.exp((lambda2*-1)*(m/12)))/(lambda2*(m/12)))-(Math.exp((lambda2*-1)*(m/12)))));
				
				// parc.taxaBrutaAno = +(Math.round(parc.taxaBrutaAno*100 + "e+2")  + "e-2");

				// if (m==1) { console.log({taxaBrutaAno: parc.taxaBrutaAno}); }
				
				parc.taxaBrutaPeriodo = (Math.pow(1+parc.taxaBrutaAno, (m/12))-1);
				parc.taxaLiquidaPeriodo = (((parc.taxaBrutaPeriodo*100)-0.025) * (1-(parc.imposto))) / 100;
				
				if (params.format=='json') parcs.push(parc);
				else parcs.push([parc.taxaBrutaAno, parc.taxaBrutaPeriodo, parc.taxaLiquidaPeriodo]);
			}

			return parcs;
		},

		getCustoOportunidade(valor=0) {
			let meses = this.carModel.periodMonths||0;

			let taxas = {0:0, 12:0.0225, 18:0.0293, 24:0.0390, 30:0.0438, 36:0.0485, 48:0.0553};
			let taxa = taxas[meses] || 0;

			let total = (valor*(1+taxa)-valor);
			
			let _money = this.$options.filters.money;
			let _toFixed = this.$options.filters.toFixed;

			let taxaInfo = `${_toFixed(taxa*100, 2)}% de ${_money(valor)}`;

			let taxaCode = [];
			for(let i in taxas) {
				if (i==0) continue;
				taxaCode.push(`${i} meses: taxa = ${_toFixed(taxas[i]*100, 2)}%;`);
			}

			taxaCode.push('');
			taxaCode.push(`Taxa escolhida: ${_toFixed(taxa*100, 2)}%`);
			taxaCode.push('');
			taxaCode.push(`Resultado: (${_money(valor)}*(1+${taxa*100})-${_money(valor)})`);
			taxaCode = taxaCode.join('<br>');

			return {valor, meses, taxa, total, taxas, taxaInfo, taxaCode};
		},

		getCalculationMonths() {
			let valorDoCarro = this.carModel.itemFipePrice; // 92000
			let ipvaPorcentagem = this.props.ipva/100;
			// let manutencao = this.getValorManutencao();
			let manutencao = this.carModel.maintenance;
			let licenciamentoSeguroObrigatorio = this.props.seguro_obrigatorio;
			let emplacamento = this.props.emplacamento;
			let entradaPorcentagem = this.props.prazo_entrada_percentagem/100;
			let entrada = valorDoCarro/100*this.props.prazo_entrada_percentagem;
			let financiamentoTaxaMesPorcentagem = this.props.prazo_juros;
			let valorFinal = this.carModel.periodPrice||0;
			let valorPromocional = this.carModel.periodPromo;
			let mesesPromocionais = this.props.ultimaParcelaPromocional;
			let periodMonths = this.carModel.periodMonths;
			let curvaJuros = this.getCurvaJuros({meses:240, format:"json"});
			let custoJuros = -1 * this.pgto((financiamentoTaxaMesPorcentagem/100), periodMonths, (valorDoCarro-entrada));

			let parcs = [];
			for(let index=0; index<=periodMonths; index++) {

				let prazoAnalise = periodMonths-index;
				let prazoAnaliseTotal = periodMonths;

				let parc = {};
				parc.mes = index;
				parc.year = Math.ceil(index/12)||1;

				parc.valorDoCarro = (() => {
					if (index==0) return valorDoCarro;

					let depreciacaoPorcentagem = (() => {
						let anual = .08;

						if (index>=0 && index<=12) {
							anual = .15;
						}
						else if (index>=13 && index<=24) {
							anual = .08;
						}
						
						let mensal = Math.pow(1-anual, 1/12);
						return {mes:index, anual, mensal};
					})();
					
					let lastValorCarro = parcs[index-1]? parcs[index-1].valorDoCarro: 0;
					return depreciacaoPorcentagem.mensal*lastValorCarro;
				})();

				parc.seguro = (() => {
					if (parc.mes==periodMonths) return 0;
					let seguroPorcentagem = this.props.seguro/100;
					return (parc.mes==0 || parc.mes%12==0)? seguroPorcentagem*parc.valorDoCarro: 0;
				})();
				
				parc.ipva = (() => {
					if (parc.mes==periodMonths) return 0;
					return (parc.mes==0 || parc.mes%12==0)? ipvaPorcentagem*parc.valorDoCarro: 0;
				})();
				
				parc.manutencao = (() => {
					return (parc.mes==0)? 0: manutencao/periodMonths;
				})();
				
				parc.licenciamentoSeguro = (() => {
					if (parc.mes==periodMonths) return 0;
					if (index==0 || parc.mes%12==0) return licenciamentoSeguroObrigatorio;
					return 0;
				})();
				
				parc.emplacamento = (() => {
					if (index==0) return emplacamento;
					return 0;
				})();

				// =SE(parc.mes=0;parc.valorDoCarro;SE(parc.mes=periodMonths;parc.valorDoCarro*-1;0))
				parc.compraVenda = (() => {
					if (parc.mes==0) return parc.valorDoCarro;
					if (parc.mes==periodMonths) return parc.valorDoCarro*-1;
					return 0;
				})();

				// =SE(parc.mes=0;financiamentoTaxaMesPorcentagem*parc.valorDoCarro;SE(parc.mes=periodMonths;parc.valorDoCarro*-1;0))
				parc.entradaVenda = (() => {
					if (index==0) return entradaPorcentagem*parc.valorDoCarro;
					else if (index==periodMonths) return parc.valorDoCarro*-1;
					return 0;
				})();

				/* retorno errado */
				parc.financiamentoParcela = (() => {
					if (index==0) return 0;
					return custoJuros;
				})();

				parc.aluguel = (() => {
					if (index==periodMonths) return 0;

					return index<mesesPromocionais?
						valorPromocional: valorFinal;
						
					// return (typeof this.carModel.item.acf.precificacao.parcels[index]=='undefined')?
					// 	this.carModel.item.acf.precificacao.parcels.slice(-1)[0]:
					// 	this.carModel.item.acf.precificacao.parcels[index];
				})();

				parc.tempoInvestimento = (() => {
					return prazoAnalise;
				})();

				parc.rentabilidade = (() => {
					let t = curvaJuros.filter(item => item.mes==parc.tempoInvestimento);
					return t[0]? t[0].taxaLiquidaPeriodo: 0;
					// return +(Math.round((t.taxaLiquidaPeriodo||0) + "e+3")  + "e-3");
				})();

				/* =((SE(parc.mes=0;
					parc.seguro+parc.ipva+parc.manutencao+parc.licenciamentoSeguro+parc.emplacamento+parc.compraVenda;
					parc.seguro+parc.ipva+parc.manutencao+parc.licenciamentoSeguro+parc.emplacamento+parc.compraVenda
				))*(1+parc.rentabilidade)) */
				parc.finalCompraAvista = (() => {
					let total = (parc.seguro+parc.ipva+parc.manutencao+parc.licenciamentoSeguro+parc.emplacamento+parc.compraVenda)*(1+parc.rentabilidade);
					if (parc.mes==periodMonths) { total = Math.min(total, total*-1); }
					return total;
				})();

				/* =SE(A4="";"";(((E4+F4+G4+H4+I4+K4+L4)*(1+$O4)))) */
				parc.finalCompraAprazo = (() => {
					let total = (parc.seguro+ parc.ipva+ parc.manutencao+ parc.licenciamentoSeguro+ parc.emplacamento+ parc.entradaVenda+ parc.financiamentoParcela) * (1+parc.rentabilidade);
					if (parc.mes==periodMonths) { total = Math.min(total, total*-1); }
					return total;
				})();

				/*
				parcela do mes seguinte
				=SE(A4="";"";
					(SE(A4=$A$2;0;PROCV(A4+1;$T$33:$V$34;3)))
					*(1+$O4)
				) */
				parc.finalAluguel = (() => {
					if (index==periodMonths) return 0;

					let finalAluguelNext = valorFinal;

					if (mesesPromocionais>0 && (parc.mes+1)<=mesesPromocionais) {
						finalAluguelNext = valorPromocional;
					}

					return finalAluguelNext*(1+parc.rentabilidade);
				})();

				parcs.push(parc);
			}

			return parcs;
		},

		getValorManutencao() {
			let maintenanceValues = [];

			let subValorManutencao = (marcaSlug, kmRodados) => {
				let maintenanceValues = [];

				let headers = ['slug', 	10000,		12000,	20000,		24000,	30000,		36000,	40000,		48000,		50000,		60000,		70000,		72000,	80000,		84000,		90000,		96000,		100000];
				let values = [
					['audi',			1589.25,	null,	1891.50,	null,	1589.25,	null,	4640.20,	null,		1589.25,	1891.50,	1589.25,	null,	4640.20,	null,		1589.25,	null,		1891.50],
					['bmw',				717.58,		null,	1814.32,	null,	1521.34,	null,	6852.12,	null,		1521.34,	1814.32,	1521.34,	null,	6852.12,	null,		1521.34,	null,		1814.32],
					['fiat',			256.80,		null,	703.20,		null,	412.80,		null,	3715.62,	null,		435.20,		947.20,		435.20,		null,	3715.62,	null,		435.20,		null,		947.20],
					['gm-chevrolet',	321.33,		null,	617.33,		null,	517.33,		null,	2177.13,	null,		497.33,		617.33,		477.33,		null,	2146.47,	null,		605.33,		null,		834.67],
					['hyundai',			210.05,		null,	468.24,		null,	455.49,		null,	1867.76,	null,		428.22,		604.59,		428.22,		null,	1867.76,	null,		455.49,		null,		885.26],
					['jeep',			null,		382.75,	226.50,		621.00,	null,		706.50,	1026.00,	2500.90,	null,		1659.75,	null,		706.50,	1026.00,	2500.90,	null,		1219.50,	440.25],
					['mercedes-benz',	1256.67,	null,	2293.33,	null,	1256.67,	null,	6585.07,	null,		1256.67,	2293.33,	1256.67,	null,	6585.07,	null,		1256.67,	null,		2293.33],
					['renault',			439.00,		null,	493.25,		null,	493.25,		null,	2440.66,	null,		547.50,		547.50,		547.50,		null,	3072.27,	null,		547.50,		null,		547.50],
					['toyota',			350.83,		null,	660.00,		null,	522.00,		null,	2389.60,	null,		522.00,		930.00,		522.00,		null,	2389.60,	null,		522.00,		null,		990.00],
					['volkswagen',		501.69,		null,	570.94,		null,	492.60,		null,	2669.92,	null,		492.60,		570.94,		492.60,		null,	2664.74,	null,		492.60,		null,		570.94],
				];

				// Descobrindo valor da manutenção baseado na tabela acima:
				// Rodando todos os items de "values":
				values.forEach(items => {

					// Se a marca do item atual não for a mesma do carro selecionado, não prossegue
					if (items[0]!=marcaSlug) return;

					// Rodando o "headers" para descobrir valor km
					headers.forEach((maxValueKm, maxValueKmIndex) => {
						if (typeof maxValueKm!='number') return;
						if (items[maxValueKmIndex]===null) return;
						if (maxValueKm>kmRodados) return;
						maintenanceValues.push(items[maxValueKmIndex]);
					});
				});

				// Retornando soma de todos os valores encontrados
				return maintenanceValues.reduce((a, b) => a + b, 0);
			};
			
			if (this.carModel.item && this.carModel.item.acf) {
				let kmRodados = this.carModel.monthKm * this.carModel.periodMonths;
				let marcaSlug = this.carModel.item.acf.informacoesModelo.marca.slug;
				let total = subValorManutencao(marcaSlug, kmRodados);

				/* 100000 é o valor máximo na tabela de preços de quilometragem. Se a quantidade de km rodados
				ultrapassar isso (o que acontece por exemplo ao selecionar período 48 meses e 3000 km/mês)
				o processo do cálculo deve ser "zerado" e o cálculo recomeça com os valores que restam */
				if (kmRodados>100000) {
					kmRodados = kmRodados-100000;
					total += subValorManutencao(marcaSlug, kmRodados);
				}

				return total;
			}

			return 0;
		},

		scrollTo(element) {
			element.scrollIntoView({ behavior: 'smooth', block: 'start'});
		},

		cacheClear() {
			this.$axios.get('/api/calculadora/cache-clear').then(resp => {
				this.$swal('Cache limpo', '', 'success');
			});
		},
	},

	computed: {
		compResult() {
			let _money = this.$options.filters.money;
			let _toFixed = this.$options.filters.toFixed;

			let data = {};
			
			/* Slug do carro na Unidas */
			data.carSlug = this.carModel.itemSlug;

			/* Por quantos meses o carro será alugado/financiado */
			data.periodMonths = this.carModel.periodMonths;

			/* Array contendo representação da tabela "Base (2)" do arquivo "Carro comprado ou alugado vf virtus v6 (1)" */
			data.calculationMonths = this.getCalculationMonths();

			/* Valor da manutenção de acordo com o carro escolhido */
			// data.maintenance = this.getValorManutencao();
			data.maintenance = this.carModel.maintenance;

			/* Valor da manutenção por mês (está sendo declarado aqui só pra ficar próximo ao valor acima) */
			data.maintenancePerMonth = data.maintenance / data.periodMonths;
			
			/* Quilometragem mensal definida pelo visitante */
			data.monthKm = this.carModel.monthKm;
			
			/* Por quantos anos o carro será alugado/fianciado de acordo com a quantidade de meses */
			// data.periodYears = Math.ceil(data.periodMonths/12);
			data.periodYears = Math.ceil((data.calculationMonths.length-1)/12);
			
			/* Valor normal da mensalidade */
			data.periodPrice = 0;
			
			/* Valor promocional da mensalidade */
			data.periodPromo = 0;
			
			/* Por quantos meses o período promocional ficará disponível */
			data.periodPromoMonths = this.props.ultimaParcelaPromocional;

			/* Valor do carro na tabela FIPE recuperado pela API */
			data.itemFipePrice = this.carModel.itemFipePrice;
			
			/* Valor do seguro obrigatório x meses */
			// data.seguro = data.periodYears*this.props.seguro_obrigatorio;
			data.seguro = data.calculationMonths.reduce((a, b) => a + b.seguro, 0);
			data.seguroInfo = '* '+ data.calculationMonths.filter(item => !!item.seguro).map(item => {
				let ano = Math.ceil((item.mes+1)/12);
				return `${_money(item.seguro)} no ${ano}º ano`;
			}).join('<br>+ ');
			data.seguroLabel = '';
			if (data.periodYears>1) { data.seguroLabel += `${_money(data.calculationMonths[0].seguro)} no 1º ano <br>`; }
			data.seguroLabel += `${_money(data.seguro)} no total`;
			
			/* Soma da coluna "ipva" da tabela data.calculationMonths */
			data.ipva = data.calculationMonths.reduce((a, b) => a + b.ipva, 0);
			data.ipvaInfo = ' &nbsp; '+ data.calculationMonths.filter(item => !!item.ipva).map(item => {
				let ano = Math.ceil((item.mes+1)/12);
				return `${_money(item.ipva)} no ${ano}º ano`;
			}).join('<br>+ ');
			data.ipvaCode = 'Soma da coluna "IPVA"';
			data.ipvaLabel = '';
			if (data.periodYears>1) { data.ipvaLabel += `${_money(data.calculationMonths[0].ipva)} no 1º ano <br>`; }
			data.ipvaLabel += `${_money(data.ipva)} no total`;
			
			/* Soma da coluna "licenciamentoSeguro" da tabela data.calculationMonths */
			data.licenciamentoSeguro = data.calculationMonths.reduce((a, b) => a + b.licenciamentoSeguro, 0);
			data.licenciamentoSeguroInfo = ' &nbsp; '+ data.calculationMonths.filter(item => !!item.licenciamentoSeguro).map(item => {
				let ano = Math.ceil((item.mes+1)/12);
				return `${_money(item.licenciamentoSeguro)} no ${ano}º ano`;
			}).join('<br>+ ');
			
			/* Emplacamento */
			data.emplacamento = 0;
			
			/* Entrada = Valor total do carro - taxa ao mês (30%) */
			data.entrada = data.itemFipePrice/100*this.props.prazo_entrada_percentagem;
			data.entradaInfo = `${this.props.prazo_entrada_percentagem}% de ${_money(data.itemFipePrice)}`;
			data.entradaCode = `${this.props.prazo_entrada_percentagem}% de ${_money(data.itemFipePrice)}`;

			/* Valor do carro a ser financiado (valor total - 30%) */
			data.financiado = data.itemFipePrice - data.entrada;
			
			/* Objeto com variáveis ambima gerado via API */
			data.anbima = this.anbima;
			
			/* Array contendo representação da tabela "Curva de juros" do arquivo "Carro comprado ou alugado vf virtus v6 (1)" */
			data.curvaJuros = this.getCurvaJuros({meses:240, format:"json"});
			
			/* Soma da coluna "emplacamento" da tabela data.calculationMonths */
			data.emplacamento = data.calculationMonths.reduce((a, b) => a + b.emplacamento, 0);
			
			/* Depreciação (média dos valores entre ano da fabricação e ano atual calculados na API) */
			data.depreciacao = this.carModel.depreciacao;
			
			/* Valor da depreciação */
			// let valorDoCarro = (data.calculationMonths.length>0? (data.calculationMonths.slice(-1)[0].valorDoCarro): 0);
			// data.depreciacao = data.itemFipePrice - valorDoCarro;
			data.depreciacaoInfo = `Valor a ser depreciado em ${data.periodMonths} meses`;
			data.depreciacaoCode = this.carModel.depreciacaoInfo;
			// data.depreciacaoCode = '<pre>'+[
			// 	`  ${_money(data.itemFipePrice)} (Valor 0Km)`,
			// 	// `- ${_money(valorDoCarro)} (Valor do carro)`,
			// ].join('<br>')+'</pre>';

			/* Valor residual */
			data.valorResidual = data.itemFipePrice - data.depreciacao;
			data.valorResidualCode = [
				`<pre class="text-white m-0">  ${_money(data.itemFipePrice)} (Valor 0Km)`,
				`- ${_money(data.depreciacao)} (Depreciação)</pre>`,
			].join('<br>');
			
			// data.juros = data.calculationMonths.filter(item => item.mes==data.periodMonths)[0].financiamentoParcela * data.periodMonths;
			data.juros = data.calculationMonths.reduce((a, b) => a + b.financiamentoParcela, 0);
			data.jurosInfo = false;
			data.jurosCode = `Soma da coluna financiamentoParcela`;
			
			// Custo de juros
			// data.custoJuros = ((data.itemFipePrice-data.entrada)-data.juros)*-1;
			// data.custoJuros = data.juros - (data.itemFipePrice-data.entrada);
			let pgto = this.pgto((this.props.prazo_juros/100), data.periodMonths, data.financiado) *-1;
			data.custoJuros = pgto * data.periodMonths - data.financiado;
			data.custoJurosInfo = `Juros de ${this.props.prazo_juros}% por ${data.periodMonths} meses`;
			data.custoJurosCode = `(PGTO(${_money(this.props.prazo_juros/100, '', 4)}; ${data.periodMonths}; ${_money(data.financiado, '', 2)}) x -1) x ${data.periodMonths} - ${_money(data.financiado)}`;
			

			data.periodPrice = 0;
			data.periodPromo = 0;
			if (this.carModel.period) {
				data.periodPrice = this.carModel.periodPrice;
				data.periodPromo = this.carModel.periodPromo;
			}

			data.valorInicial = data.itemFipePrice;
			data.valorInicialCode = 'Valor inicial do carro na tabela de valores';

			data.valorFinal = data.calculationMonths.length==0? 0: data.calculationMonths.filter(item => item.mes==data.periodMonths)[0].valorDoCarro;
			data.valorFinalCode = 'Valor final do carro na tabela de valores';

			// =-((data.valorFinal/data.valorInicial)^(1/3)-1)
			data.depreciacaoAnual = ((data.valorFinal/data.valorInicial)**(1/3)-1)*-1;
			data.depreciacaoAnualCode = `((${_money(data.valorFinal)}/${_money(data.valorInicial)})**(1/3)-1)*-1`;

			data.results = [];


			// COLUNA COMPRA FINANCIADA
			data.results.push((() => {
				let result = {
					id: "compraFinanciada",
					title: "Compra financiada",
					bootstrapClass: "primary",
					investimentoTotal: 0,
					investimentoTotalInfo: '',
					investimentoTotalCode: '',
					retornoVenda: 0,
					aporteSemRetorno: 0,
					emplacamento: 0,
					manutencaoAnual: 0,
					depreciacao: 0,
					custoOportunidade: 0,
					custoOportunidadeInfo: '',
					custoMedioMensal: 0, // investimentoTotal/meses
					best: false,
					items: [],
				};

				let custoOportunidadeAvista = data.calculationMonths.reduce((a, b) => a+b.finalCompraAprazo, 0) - (
					data.calculationMonths.reduce((a, b) => a+b.seguro, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.ipva, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.manutencao, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.licenciamentoSeguro, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.emplacamento, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.entradaVenda, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.financiamentoParcela, 0)
				);

				let custoOportunidadeAvistaInfo = '';
				let custoOportunidadeAvistaCode = 'Soma das colunas "finalCompraAvista" - ("seguro" + "ipva" + "manutencao" + "licenciamentoSeguro" + "emplacamento")';

				// Valor total
				result.investimentoTotal = [
					data.seguro,
					data.ipva,
					data.licenciamentoSeguro,
					data.emplacamento,
					data.maintenance,
					data.depreciacao,
					custoOportunidadeAvista,
					data.custoJuros,
				].reduce((a, b) => a+b, 0);

				result.investimentoTotalInfo = `Seguro + IPVA + Licenciamento e DPVAT + Emplacamento + Manutenção + Depreciação + Custo oportunidade + Custo juros`;
				result.investimentoTotalTooltip = result.investimentoTotalCode = [
					`<pre class="text-white m-0">  ${_money(data.seguro)} Seguro`,
					`+ ${_money(data.ipva)} IPVA`,
					`+ ${_money(data.licenciamentoSeguro)} Licenciamento+DPVAT`,
					`+ ${_money(data.emplacamento)} Emplacamento`,
					`+ ${_money(data.maintenance)} Manutenção`,
					`+ ${_money(data.depreciacao)} Depreciação`,
					`+ ${_money(custoOportunidadeAvista)} Custo oportunidade`,
					`+ ${_money(data.custoJuros)} Custo juros</pre>`,
				].join('<br>');

				// itens da lista
				result.items.push({
					text: `Seguro: <br>${_money(data.seguro)}`,
					obs: data.seguroInfo,
					code: 'Soma da coluna "seguro"',
					tooltip: `SEGURO<br>O custo do seguro é em média de ${this.props.seguro}% do preço de compra do carro. No Livre, você não se preocupa com seguro.`,
				});

				result.items.push({
					text: `IPVA: <br>${_money(data.ipva)}`,
					obs: data.ipvaInfo,
					code: data.ipvaCode,
					tooltip: `DOCUMENTAÇÃO<br>O custo da documentação é anual. Sendo: o IPVA em média ${this.props.seguro}% do valor de compra do veículo (varia conforme estado),
					o licenciamento em média ${_money(data.calculationMonths[0]? data.calculationMonths[0].licenciamentoSeguro: 0)} (varia conforme estado)
					e o DPVAT de R$ 16,21, independente da localidade. No Livre, todos os custos de manutenção estão inclusos na mensalidade.`,
				});

				result.items.push({
					text: `Licenciamento + DPVAT: <br>${_money(data.licenciamentoSeguro)}`,
					obs: data.licenciamentoSeguroInfo,
					code: 'Soma da coluna "licenciamentoSeguro"',
					tooltip: `LICENCIAMENTO + DPVAT<br>O licenciamento em média R$100,00 (varia conforme o estado) e o DPVAT de R$ 16,21 independente da localidade.`,
				});

				result.items.push({
					text: `Emplacamento: <br>${_money(data.emplacamento)}`,
					obs: '* Apenas no primeiro mês',
					code: 'Soma da coluna "emplacamento"',
					tooltip: `EMPLACAMENTO<br>O valor do emplacamento e despachante é cobrado somente no 1° ano do carro e custa em média ${_money(this.props.emplacamento)}. No Livre, você não tem esse custo.`,
				});

				result.items.push({
					text: `Manutenção: <br>${_money(data.maintenance)}`,
					obs: `* ${_money(data.maintenancePerMonth)}/mês`,
					code: 'Valor gerado dependendo da marca do carro',
					tooltip: `MANUTENÇÃO<br>O custo de manutenção são as revisões previstas em manual (a cada 10.000 KM para a maioria dos carros e a cada 12.000 KM para
					Renegade e Compass) feitas na concessionária somado a troca dos pneus a cada 40.000 KM. No Livre, você não se preocupa
					com as despesas de manutenção preventiva.`,
				});

				result.items.push({
					text: `Depreciação: <br>${_money(data.depreciacao)}`,
					obs: data.depreciacaoInfo,
					code: data.depreciacaoCode,
					tooltip: [
						`Valor de compra do carro: <br>${_money(data.itemFipePrice)}`,
						`Valor de venda após ${data.periodMonths} meses: <br>${_money(data.valorResidual)}`,
						`Resultado da perda: <br>${_money(data.itemFipePrice-data.valorResidual)}`,
					].join('<div style="height:5px;"></div>'),
				});
				
				// result.items.push({
				// 	text: `Valor residual: <br>${_money(data.valorResidual)}`,
				// 	obs: `Valor de venda do carro após ${data.periodMonths} meses de uso`,
				// 	code: data.valorResidualCode,
				// });

				// result.items.push({
				// 	text: `Custo de oportunidade financiamento: <br>${_money(result.custoOportunidade)}`,
				// 	obs: result.custoOportunidadeInfo,
				// 	code: custoOportunidade.taxaCode,
				// });

				result.items.push({
					text: `Custo de oportunidade financiamento: <br>${_money(custoOportunidadeAvista)}`,
					obs: custoOportunidadeAvistaInfo,
					code: custoOportunidadeAvistaCode,
					tooltip: 'O custo de oportunidade é o valor que você ganharia caso tivesse investido a grana da compra do carro ou da entrada (em caso de financiamento) no tesouro direto. No Livre, você não se preocupa com o alto investimento de comprar um carro e pode aplicar o seu dinheiro em outro sonho!',
				});

				result.items.push({
					text: `Custo do juros: <br>${_money(data.custoJuros)}`,
					obs: data.custoJurosInfo,
					code: data.custoJurosCode,
				});

				return result;
			})());



			// COLUNA COMPRA A VISTA
			data.results.push((() => {
				let result = {
					id: "compraVista",
					title: "Compra à vista",
					bootstrapClass: "primary",
					investimentoTotal: 0,
					investimentoTotalInfo: '',
					investimentoTotalCode: '',
					retornoVenda: 0,
					aporteSemRetorno: 0,
					emplacamento: 0,
					manutencaoAnual: 0,
					depreciacao: 0,
					custoOportunidade: 0,
					custoOportunidadeInfo: '',
					custoMedioMensal: 0, // investimentoTotal/meses
					best: false,
					items: [],
				};

				let custoOportunidadeAvista = data.calculationMonths.reduce((a, b) => a+b.finalCompraAvista, 0) - (
					data.calculationMonths.reduce((a, b) => a+b.seguro, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.ipva, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.manutencao, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.licenciamentoSeguro, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.emplacamento, 0)
					+ data.calculationMonths.reduce((a, b) => a+b.compraVenda, 0)
				);

				let custoOportunidadeAvistaInfo = '';
				let custoOportunidadeAvistaCode = 'Soma das colunas "finalCompraAvista" - ("seguro" + "ipva" + "manutencao" + "licenciamentoSeguro" + "emplacamento" + "compraVenda")';


				result.aporteSemRetorno = data.calculationMonths.reduce((a, b) => a+b.finalCompraAvista, 0);
				result.retornoVenda = data.calculationMonths.length==0? 0: data.calculationMonths.filter(item => item.mes==data.periodMonths)[0].valorDoCarro;
				
				// Valor total
				result.investimentoTotal = [
					data.seguro,
					data.ipva,
					data.licenciamentoSeguro,
					data.emplacamento,
					data.maintenance,
					data.depreciacao,
					custoOportunidadeAvista,
				].reduce((a, b) => a+b, 0);

				result.investimentoTotalInfo = `Seguro + IPVA + Licenciamento e DPVAT + Emplacamento + Manutenção + Depreciação + Custo oportunidade`;
				result.investimentoTotalTooltip = result.investimentoTotalCode = [
					`<pre class="text-white m-0">  ${_money(data.seguro)} Seguro`,
					`+ ${_money(data.ipva)} IPVA`,
					`+ ${_money(data.licenciamentoSeguro)} Licenciamento+DPVAT`,
					`+ ${_money(data.emplacamento)} Emplacamento`,
					`+ ${_money(data.maintenance)} Manutenção`,
					`+ ${_money(data.depreciacao)} Depreciação`,
					`+ ${_money(custoOportunidadeAvista)} Custo oportunidade`,
				].join('<br>');


				// itens da lista
				result.items.push({
					text: `Seguro: <br>${_money(data.seguro)}`,
					obs: data.seguroInfo,
					code: 'Soma da coluna "seguro"',
					tooltip: `SEGURO<br>O custo do seguro é em média de ${this.props.seguro}% do preço de compra do carro. No Livre, você não se preocupa com seguro.`,
				});

				result.items.push({
					text: `IPVA: <br>${_money(data.ipva)}`,
					obs: data.ipvaInfo,
					code: 'Soma da coluna "ipva"',
					tooltip: `DOCUMENTAÇÃO<br>O custo da documentação é anual. Sendo: o IPVA em média ${this.props.seguro}% do valor de compra do veículo (varia conforme estado),
					o licenciamento em média ${_money(data.calculationMonths[0]? data.calculationMonths[0].licenciamentoSeguro: 0)} (varia conforme estado)
					e o DPVAT de R$ 16,21, independente da localidade. No Livre, todos os custos de manutenção estão inclusos na mensalidade.`,
				});

				result.items.push({
					text: `Licenciamento + DPVAT: <br>${_money(data.licenciamentoSeguro)}`,
					obs: data.licenciamentoSeguroInfo,
					code: 'Soma da coluna "licenciamentoSeguro"',
					tooltip: `LICENCIAMENTO + DPVAT<br>O licenciamento em média R$100,00 (varia conforme o estado) e o DPVAT de R$ 16,21 independente da localidade.`,
				});

				result.items.push({
					text: `Emplacamento: <br>${_money(data.emplacamento)}`,
					obs: '* Apenas no primeiro mês',
					code: 'Soma da coluna "emplacamento"',
					tooltip: `EMPLACAMENTO<br>O valor do emplacamento e despachante é cobrado somente no 1° ano do carro e custa em média ${_money(this.props.emplacamento)}. No Livre, você não tem esse custo.`,
				});

				result.items.push({
					text: `Manutenção: <br>${_money(data.maintenance)}`,
					obs: `* ${_money(data.maintenancePerMonth)}/mês`,
					code: 'Valor gerado dependendo da marca do carro',
					tooltip: `MANUTENÇÃO<br>O custo de manutenção são as revisões previstas em manual (a cada 10.000 KM para a maioria dos carros e a cada 12.000 KM para
					Renegade e Compass) feitas na concessionária somado a troca dos pneus a cada 40.000 KM. No Livre, você não se preocupa
					com as despesas de manutenção preventiva.`,
				});

				result.items.push({
					text: `Depreciação: <br>${_money(data.depreciacao)}`,
					obs: `Média anual de todos os valores FIPE do modelo`,
					code: this.carModel.depreciacaoInfo,
					// code: `Pega todos os valores FIPE desde o ano de fabricação da marca (ou do carro) e tira a média (este valor especificamente é gerado pelo endpoint em api.php?method=fipePrice)`,
					tooltip: [
						`Valor de compra do carro: <br>${_money(data.itemFipePrice)}`,
						`Valor de venda após ${data.periodMonths} meses: <br>${_money(data.valorResidual)}`,
						`Resultado da perda: <br>${_money(data.itemFipePrice-data.valorResidual)}`,
					].join('<div style="height:5px;"></div>'),
				});

				result.items.push({
					text: `Custo de oportunidade à vista: <br>${_money(custoOportunidadeAvista)}`,
					obs: custoOportunidadeAvistaInfo,
					code: custoOportunidadeAvistaCode,
					tooltip: 'O custo de oportunidade é o valor que você ganharia caso tivesse investido a grana da compra do carro ou da entrada (em caso de financiamento) no tesouro direto. No Livre, você não se preocupa com o alto investimento de comprar um carro e pode aplicar o seu dinheiro em outro sonho!',
				});

				return result;
			})());

			

			
			// COLUNA ASSINATURA
			data.results.push((() => {
				let result = {
					id: "aluguel",
					title: "Assinatura",
					bootstrapClass: "primary",
					investimentoTotal: 0,
					investimentoTotalInfo: '',
					investimentoTotalCode: '',
					retornoVenda: 0,
					aporteSemRetorno: 0,
					emplacamento: 0,
					manutencaoAnual: 0,
					depreciacao: 0,
					custoOportunidade: 0,
					custoOportunidadeInfo: '',
					custoMedioMensal: 0, // investimentoTotal/meses
					best: false,
					items: [],
				};

				let custoOportunidade = this.getCustoOportunidade(data.entrada);
				result.custoOportunidade = custoOportunidade.total;
				result.custoOportunidadeInfo = `${_toFixed(custoOportunidade.taxa*100, 2)}% de ${_money(data.entrada)} (valor da entrada)`;




				result.aporteSemRetorno = data.calculationMonths.reduce((a, b) => a+b.finalAluguel, 0);
				result.retornoVenda = 0;

				// data.financiamentoParcela * data.periodMonths
				result.investimentoTotal = data.calculationMonths.reduce((a, b) => a + b.aluguel, 0);

				result.investimentoTotalInfo = `Soma das ${data.calculationMonths.length-1} mensalidades <br>`+ [
					(data.periodPromoMonths>0? null: `${_money(data.periodPrice)} do 1º ao último mês`),
					(data.periodPromoMonths==0? null: `${_money(data.periodPromo)} do 1º ao ${data.periodPromoMonths}º mês`),
					(data.periodPromoMonths==0? null: `${_money(data.periodPrice)} do ${data.periodPromoMonths+1}º ao último mês`),
				].filter(item => item).join('<br>');

				result.investimentoTotalCode = '<pre class="text-white m-0">  '+ data.calculationMonths.filter(item => !isNaN(item.aluguel)).map((item, index) => {
					return `${_money(item.aluguel)} (Mês ${index})`;
				}).join('<br>+ ') +'</pre>';

				// result.items.push({
				// 	text: `Juros: <br>${_money(data.juros)}`,
				// 	obs: data.jurosInfo,
				// 	code: '??',
				// });
				
				result.investimentoTotal = data.calculationMonths.reduce((a, b) => a+b.finalAluguel, 0) || 0;
				result.investimentoTotalTooltip = 'Soma das mensalidades da assinatura';
				result.investimentoTotalCode = 'Soma da coluna "finalAluguel" da tabela de valores';

				let custoAssinatura = data.calculationMonths.reduce((a, b) => a+b.aluguel, 0) || 0;
				let custoRentabilidadeAssinatura = result.investimentoTotal - custoAssinatura;

				result.items.push({
					text: `Custo Assinatura: <br>${_money(custoAssinatura)}`,
					obs: 'Total dos custos de assinatura mensais',
					code: `Soma da coluna "aluguel" da tabela de valores`,
				});

				result.items.push({
					text: `Custo rentabilidade assinatura: <br>${_money(custoRentabilidadeAssinatura)}`,
					obs: 'Diferença do investimento total para o custo de assinatura',
					code: `${_money(result.investimentoTotal)} - ${_money(custoAssinatura)}`,
				});
				
				return result;
			})());


			// Melhor resultado
			let valueMin = Math.min.apply(null, data.results.map(item => item.investimentoTotal));
			data.results = data.results.map(result => {
				result.best = result.investimentoTotal == valueMin;
				return result;
			});

			

			data.chart2 = false;
			if (data.calculationMonths.length>0) {
				data.chart2 = {};
				data.chart2.higher = Math.max(...data.results.map(o => o.investimentoTotal), 0) || 0;
				data.chart2.items = data.results.map(item => {
					item.chartPercent = item.investimentoTotal*100/data.chart2.higher;
					return item;
				});
			}


			// Apex chart settings
			data.chart = false;
			if (data.calculationMonths.length>0) {
				data.chart = {
					type: "bar",
					animations: {enabled:false},
					options: {
						chart: {
							selection: {
								enabled: false,
								fill: {opacity: 1},
							}
						},
						fill: {
							opacity: 1,
							type: "solid",
							colors: ['#449fdf', '#227dbf', '#005b9e'],
						},
						plotOptions: {
							fill: {opacity: 1},
							bar: {
								colors: {
									backgroundBarOpacity: 1,
									backgroundBarRadius: 5,
								},
							},
						},
						states: {
							normal: {filter: {type: 'none'}},
							hover: {filter: {type: 'none'}},
							hover: {filter: {type: 'none'}, allowMultipleDataPointsSelection: true},
						},
						tooltip: {enabled: false},
						dataLabels: {
							enabled: true,
							formatter:(value, series) => {return _money(value); },
							background: {opacity:1},
						},
						xaxis: {
							categories: data.results.map(item => item.title),
							background: {opacity:1},
						},
						yaxis: {
							show: false,
							labels: {enabled: false},
							background: {opacity:1},
						},
						legend: {show:false, floating:false},
						responsive: [
							{
								breakpoint: 576,
								options: {
									plotOptions: {
										bar: {
											horizontal: true,
										},
									},
								},
							},
						],
					},
					series: [{
						name: "Valor",
						data: data.results.map(item => item.investimentoTotal),
					}],
				};
			}

			return data;
		},
	},

	data() {
		return {
			test: /[?&]test=/.test(location.search), // Define se está em modo de teste
			testConsole: false, // Define se console está aberto/fechado
			props: Object.assign({}, this.$props),

			// Novo
			carModel: {
				loading: 0,
				maintenance: 0, // Este valor deve vir da API
				maintenanceMonthly: 0, // Este valor deve vir da API
				maintenanceDescription: '', // Este valor deve vir da API
				periodMonths: 12,
				periodPrice: 0,
				periodPromo: 0,
				itemFipePrice: 0,
				itemFipeCode: 0,
				
				depreciacaoPercentMes: '', // Este valor deve vir da API
				depreciacaoPercentContrato: '', // Este valor deve vir da API
				depreciacao: 0,
				depreciacaoInfo: '',
				prazoEntrada: 0,
				prazoPrice: 0,
				period: [],
				periods: [],
				monthKm: 1000,
				simulation: false,
				itemSlug: "",
				item: false,
				items: [],
			},

			veiculos: [],

			// Curva anbima | https://www.anbima.com.br/informacoes/est-termo/CZ.asp
			// Download XML: POST https://www.anbima.com.br/informacoes/est-termo/CZ-down.asp {idioma:"PT", Dt_Ref:"", saida:"XML"}
			anbima: {
				beta1: 0.113688743,
				beta2: -0.091928345,
				beta3: -0.0965959146620378,
				beta4: -0.0852914294108163,
				lambda1: 1.67721725506874,
				lambda2: 0.280443426365157,
			},

			maintenances: {
				'audi': 1589.25,
				'bmw': 717.58,
				'fiat': 256.80,
				'gm-chevrolet': 321.33,
				'hyundai': 210.05,
				'jeep': 382.75,
				'mercedes-benz': 1256.67,
				'renault': 439,
				'toyota': 350.83,
				'volkswagen': 501.69,
				'ford': 0,
			},
		};
  	},
};
</script>



<style>
@import url('https://fonts.googleapis.com/css2?family=Exo:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

.samydana-best-option {
	font-family: Exo;
	font-style: normal;
	font-weight: bold;
	font-size: 16px;
	line-height: 21px;
	color: #FFFFFF;
	padding: 5px;
	text-align: center;
	text-transform: uppercase;
}

.samydana-calc * {transition: all 300ms ease;}
.samydana-calc a, .samydana-calc a:hover {
	font-family: Exo;
	font-style: normal;
	font-weight: 600;
	font-size: 14px;
	line-height: 19px;
	/* identical to box height */

	letter-spacing: -0.02em;
	text-transform: uppercase;
	color: #3CD5EA;
}
.samydana-calc .text-primary {color: #325D94 !important;}

.samydana-calc .text-primary svg {}
.samydana-calc .text-primary svg path  {fill: #325D94 !important;}
.samydana-calc .text-primary svg circle {stroke: #325D94 !important;}
.samydana-calc .text-primary .card-title-text {} {color: #325D94 !important;}

.samydana-calc .text-success svg {}
.samydana-calc .text-success svg path {fill: var(--success) !important;}
.samydana-calc .text-success svg circle {stroke: var(--success) !important;}
.samydana-calc .text-primary .card-title-text {} {color: --success !important;}

.samydana-calc .text-danger svg {}
.samydana-calc .text-danger svg path {fill: var(--danger) !important;}
.samydana-calc .text-danger svg circle {stroke: var(--danger) !important;}
.samydana-calc .text-primary .card-title-text {} {color: --danger !important;}

.samydana-calc-title {
	font-family: Exo;
	font-style: normal;
	font-weight: bold;
	font-size: 28px;
	line-height: 37px;
	text-transform: uppercase;
	color: #325D94;
	border-left: solid 5px #FFDD00;
	padding-left: 10px;
}

.el-loading-mask {background:#ffffff88;}

.samydana-calc .form-control:focus {box-shadow:none!important; outline:0!important;}

.samydana-calc .input-group .input-group-text {background-color:transparent;}
.samydana-calc .form-group label {
	font-family: Exo;
	font-style: normal;
	font-weight: 600;
	font-size: 18px;
	line-height: 24px;
	color: #333333;
	margin-bottom: 15px;
}

.samydana-calc .form-control {
	font-family: Roboto;
	font-style: normal;
	font-weight: 500;
	font-size: 14px;
	line-height: 16px;
	color: #333333;
}

.samydana-calc .card-body {
	font-family: Roboto;
	font-size: 16px;
	font-style: normal;
	font-weight: 400;
	line-height: 19px;
	letter-spacing: 0em;
	text-align: left;
}

.bg-blue {background:#004072;}

.btn-yellow-custom {
	color: #016EA7 !important;
	padding: 15px 0px !important;
}

.samydana-calc-footer-obs {
	font-family: Roboto;
	font-style: normal;
	font-weight: normal;
	font-size: 16px;
	line-height: 19px;
	color: #333333;
}

.samydana-calc .card {
	background: #FAFAFA;
	border: 1px solid #C4C4C4;
	box-sizing: border-box;
	border-radius: 4px;
	height: 100%;
	padding: 15px;
}

.pattern-hlines {
	background-color: transparent;
	background-size: 40px 40px;
	background-position: 0px -5px;
	background-image: repeating-linear-gradient(0deg, #ddd, #ddd 1px, transparent 1px, transparent);
}

.pattern-vlines {
	background-color: transparent;
	background-size: 40px 40px;
	background-position: 7px 0px;
	background-image: repeating-linear-gradient(90deg, #ddd, #ddd 1px, transparent 1px, transparent);
}

.samydana-calc {position:relative;}
.samydana-calc .table-fixed-header {overflow-y: scroll; max-height:300px;}
.samydana-calc .table-fixed-header th {position: sticky; top: 0; background:#f5f5f5;}
.samydana-calc .table-test th, .samydana-calc .table-test td {white-space:pre; font-family:monospace; font-size:14px;}

.samydana-calc-code {background: #444; color: #eee; padding: 2px 5px; font-family: monospace !important;}
.samydana-calc-code pre {color:#fff; margin:0px;}

.table caption, .table-caption {background:#ddd; font-weight:bold; text-transform:uppercase; padding:10px 10px;}

.el-tabs__item {
	font-family: Roboto !important;
	font-style: normal;
	font-weight: 500;
	font-size: 14px;
	color: #333333;
	text-transform: uppercase;
	padding-left: 10px !important;
	padding-right: 10px !important;
}

.el-tabs-pane-hidden .el-tabs__content {max-height: 0px; overflow: hidden;}
.el-tabs-pane-hidden .el-tabs__header {margin:0px;}
</style>