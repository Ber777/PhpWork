

	


	<!-- AUTOCOMPLETE

   
    <script type="text/javascript" src="js/jquery.autocomplete.js"></script>
    <script src="js/jquery.ui.position.js" type="text/javascript" "></script>
    
   
      -->
      
   <script id="search-template-source" type="text/x-handlebars-template">
   
                {{#each data}}
			    <form id="form{{@index}}" class="descriptors-form" action="">
    				<div class="content-actions">
    					<p class="add-tag content-action click-button">Добавить тег</p>
    					<p class="add-not-tag content-action click-button">Отсутствие тега</p>
    					<p class="add-attr content-action click-button">Добавить атрибут</p>
    					<p class="check content-action click-button"><img src="/images/search.png" alt="Побробно"></p>
    					<p class="edit-group content-action click-button">Редактировать группу</p>
    
    				</div>
    				<div class="descriptors-content">
    					{{#each this}}
                            {{#switch this.id}}
                        
         <div class='item {{#case 1}} tag{{/case}} {{#case 2}} attr {{/case}} {{#case 3}} not-tag {{/case}}' {{#unless @first}} style='position: relative; margin-left: 30px;'{{/unless}}>
                                        <span class="value">{{this.name}}</span>
                                        <span class="edit"></span>
                                        <span style="pointer-events: auto;" class="remove">×</span>
                                        {{#unless @first}}<div class="conjunction"></div>{{/unless}}
                                    </div>
                                                             
                            {{/switch}}
                        {{/each}}
    				</div>

                </form>
                {{/each}}
              
   </script>

	


	<div class="modal-window-tree"></div>



		 <div id="wrapper">
		 		<h3 class="padding-for-h1 center-in-div">Расширенный поиск</h3>
		<form>
		    <label style="margin-right: 10px">Имя поискового шаблона:</label><input style="width: 400px" class="standart-input" type="text" value="<?=$this->name_current_object ?>" id="name-template" placeholder="Введите имя шаблона"/>
		    <p>
		        <input type="hidden" id="id-template" value="<?=$this->id_current_object ?>">
		        <input type="hidden" id="json-template" value="">
		        <input class="click-button save-templates-from-bigsearch" data-ajax="/bigsearch/ajaxTemplateAlert/" type="button" value="Сохранить для оповещений">
				<input class="click-button save-templates-from-bigsearch" data-ajax="/bigsearch/ajaxTemplateSearch/" type="button" value="Сохранить как шаблон">
		    </p>
		    <p>

<!-- <script>     function tree-s() {
		alert('jdhjs');
		}
   </script>
			<input type="button" onclick="tree-s()" value="Выбрать место поиска">
				<input type="button" onclick="tree-s()" id="select_place_tree" value="Выбрать место поиска"> 			 -->
<!--<?function trs(){
include 'tree_all.html';
}
if($_POST){trs();}?>
<form method="post">
<input type="button" value="Выбрать место поиска">
</form> -->
<a href="/_fw/templates/primary_blocks/bigsearch/tree_all.php">
<input type="button"  value="Полный каталог">
</a>

			</p>
		</form>



        <div style="display: none;" id="search-template">
            <p id="search-template-id"><?= $this->id_current_object ?></p>
            <p id="search-template-name"><?= $this->name_current_object ?></p>
            <p id="search-template-content"><?= $this->sql_text ?></p>
        </div>
								
		<section class="main">
            <div class="groups-manage-aligment">
                <button id="groupsAdder" class="content-action click-button">Добавить группу условий</button>
                <button id="groups-viewer" class="content-action click-button">Просмотр всех групп</button>
            </div>
		


			<form id="form0" class="descriptors-form" action="">
				<div class="content-actions">
					<p class="add-tag content-action click-button">Добавить тег</p>
					<p class="add-not-tag content-action click-button">Отсутствие тега</p>
					<p class="add-attr content-action click-button">Добавить атрибут</p>
					<p class="check content-action click-button"><img src="/images/search.png" alt="Побробно"></p>
					<p class="edit-group content-action click-button">Редактировать группу</p>

				</div>
				<div class="descriptors-content">
					<p class="empty-content">Добавьте дескрипторы</p>
				</div>

			</form>   


	<button value="Найти" class="click-button" id="btn">Найти</button> 

	<div id="results">
		<p>Задайте условия поиска</p>
	</div>

	<div id="modal-windows">
    
    
    
			<!-- Модальные окна -->	
	<div id="modal-tags-add" class="modal-window" style="display:none;">
		<div class="modal-title">
			<p class="title">Введите новый тег</p>
		</div>
		<span class="modal-close">×</span>
		<div class="modal-content">
			<input type="text" placeholder="Введите новый тег" id="input-tag" class="input">
			<span class="modal-action add-action-button">Добавить</span>
			<input type="hidden" class="descriptor-id" name="1">
			<input type="hidden" class="form-id">
            <div class="message" style="display: none;">
                <span class="message-info">Дескриптор добавлен</span>
            </div>
		</div>
		<div class="autocomplete-aligm"></div>
		
	</div>
	<div id="modal-not-tags-add" class="modal-window" style="display:none;">
		<div class="modal-title">
			<p class="title">Введите отсутствующий тег</p>
		</div>
		<span class="modal-close">×</span>
		<div class="modal-content">
			<input type="text" placeholder="Введите новый тег" id="input-not-tag" class="input">
			<span class="modal-action add-action-button">Добавить</span>
			<input type="hidden" class="descriptor-id" name="3">
			<input type="hidden" class="form-id">
            <div class="message" style="display: none;">
                <span class="message-info">Дескриптор добавлен</span>
            </div>
		</div>
		<div class="autocomplete-aligm"></div>
	</div>
	<div id="modal-attr-add" class="modal-window" style="display:none;">
		<div class="modal-title">
			<p class="title">Добавьте новый атрибут</p>
		</div>
		<span class="modal-close">×</span>
		<div class="modal-content">
			<input type="text" id="input-attr" placeholder="Введите новый атрибут" id="input-attr" class="input">
			<span class="modal-action add-action-button">Добавить</span>
			<select name="relation" class="rel-select">
				<option selected="selected" disabled="" value="default">Отношение</option>
				<option value="=">=</option>
				<option value="!=">!=</option>
				<option value=">">></option>
				<option value="<"><</option>
				<option value="like">~</option>
			</select>
			<input type="text" class="attr-number input" placeholder="Значение">

			<input type="hidden" class="descriptor-id" name="2">
			<input type="hidden" class="form-id">
            <div class="message" style="display: none;">
                <span class="message-info">Дескриптор добавлен</span>
            </div>
		</div>
		<div class="autocomplete-aligm"></div>
	</div>
    <!--Редактирование одного тега -->
	<div id="modal-edit-descriptor" class="modal-window" style="display:none;">
		<div class="modal-title">
			<p class="title">Редактировать дескриптор</p>
			<span class="modal-close">×</span>
		</div>
		<div class="modal-content">
			<input type="text" class="edit-desc input">
			<span class="modal-action edit-action-button">Сохранить</span>
			<input type="hidden" class="descriptor-id" name="3">
			<input type="hidden" class="form-id" name="0">
		</div>
	</div>
    <!--Редактирование одного тега -->
    
    <!-- Редактирование формы -->
	<div id="edit-group" class="edit-window" style="display:none;">
		<div class="modal-title">
			<p class="title">Редактировать группу</p>
		</div>
		<span class="modal-close">×</span>
		<div class="edit-content modal-content">
			<div id="descriptors">
			<!--
				<div class="descriptor-item tag-edit">
					<input type="text" class="edit-input-tag input">
					<input type="hidden" class="descriptor-id" name="1">
				</div>
				<div class="descriptor-item attr-edit">

					<input type="text" class="edit-input-attr input">
					<select name="relation" class="rel-select">
						<option selected="selected" disabled="" value="default">Отношение</option>
						<option value="equal">=</option>
						<option value="notequal">!=</option>
						<option value="right">></option>
						<option value="left"><</option>
						<option value="like">~</option>
					</select>
					<input type="text" class="attr-number input">
					<input type="hidden" class="descriptor-id" name="2">

				
				</div>

				<div class="descriptor-item not-tag-edit">
					<input type="text" class="edit-input-not-tag input">
					<input type="hidden" class="descriptor-id" name="3">
				</div>
				-->
			</div>

			
			<input type="hidden" class="form-id">
			<div class="edit-action" style="display:none;">
				<span class="modal-action edit-action-button">Сохранить</span>
			</div>
            <div class="message" style="display: none;">
                <span class="message-info">Отредактировано</span>
            </div>
		</div>
	</div> 
    <!-- Редактирование формы -->
    
    
    <div id="view-groups" class="modal-window" style="display: none;">
        <div class="modal-title">
            <p class="title">Просмотр всех групп</p>
            
        </div>
        <span class="modal-close">×</span>
        <div class="modal-content">
            <div class="groups-aligment">
            <!--
                <div class="conditions-group">
                    <div class="included-tags">
                        <div class="included-title"><p>Теги: </p></div>
                        <div class="included-tags-content">
                            <div class="item">
                                <span class="value">ce kavo</span>
                            </div>
                            <div class="item">
                                <span class="value">1312</span>
                            </div>
                            <div class="item">
                                <span class="value">ce kavo</span>
                            </div>
                            <div class="item">
                                <span class="value">1312</span>
                            </div>
                            <div class="item">
                                <span class="value">ce kavo</span>
                            </div>
                            <div class="item">
                                <span class="value">1312</span>
                            </div>
                            <div class="item">
                                <span class="value">ce kavo</span>
                            </div>
                            <div class="item">
                                <span class="value">1312</span>
                            </div>
                            <div class="item">
                                <span class="value">ce kavo</span>
                            </div>
                            <div class="item">
                                <span class="value">1312</span>
                            </div>
                        </div>
                    </div>
                    <div class="included-not-tags">
                        <div class="included-title"><p>Отсутствие тегов:</p></div>
                        <div class="included-not-tags-content">
                            <div class="item">
                                <span class="value">ce kavo</span>
                            </div>
                            <div class="item">
                                <span class="value">1312</span>
                            </div>
                        </div>
                    </div>
                    <div class="included-attributes">
                        <div class="included-title"><p>Атрибуты: </p></div>
                        <div class="included-attributes-content">
                            <div class="item">
                                <span class="value">Год = 2010</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="conditions-group">
                <div class="disjunction"></div>
                    <div class="included-tags">
                        <div class="included-title"><p>Теги: </p></div>
                        <div class="included-tags-content">
                            <div class="item">
                                <span class="value">ce kavo</span>
                            </div>
                            <div class="item">
                                <span class="value">1312</span>
                            </div>
                        </div>
                    </div>
                    <div class="included-not-tags">
                        <div class="included-title"><p>Отсутствие тегов:</p></div>
                        <div class="included-not-tags-content">
                            <div class="item">
                                <span class="value">ce kavo</span>
                            </div>
                            <div class="item">
                                <span class="value">1312</span>
                            </div>
                        </div>
                    </div>
                    <div class="included-attributes">
                        <div class="included-title"><p>Атрибуты: </p></div>
                        <div class="included-attributes-content">
                            <div class="item">
                                <span class="value">Автор = Суходрищев</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                -->
                
            </div>
        </div>
    </div>


	<!-- Модальные окна -->	
    <!-- Индикатор загрузки -->
    <div class="loader" style="display: none;">
        <div class="cssload-container">
            <div class="cssload-double-torus"></div>
        </div>
    </div>
    <!-- Индикатор загрузки -->
    
	</div>
	</section>
	<div class="overlay"></div>
