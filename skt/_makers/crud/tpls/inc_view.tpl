<div class="ibe-box">
	<span class="ibe-button">
		<?php $this->helper->linkapp->_($this->module,$this->controller,$this->action,'','Novo');?>							
	</span>
	<button class="ibe-form-hide">Esconder form</button>
	<button class="ibe-grid-hide">Esconder grid</button>
</div>

<div class="ibe-box" id="app-form">
	<span class="clear"></span>
	
	<?php if($this->errors):?>
		<span class="ibe-box-error">
			<?php echo $this->errors;?>
		</span>
	<?php endif;?>
	
	<?php if($this->salvo):?>
		<span class="ibe-box-success">Registro salvo com sucesso</span>
	<?php endif;?>
	
	
	<?php if($this->excluido):?>
		<span class="ibe-box-success">Registro excluido com sucesso</span>
	<?php endif;?>		
	
	<?php $this->autor_form->show(); ?>
</div>

<div class="ibe-box" id="app-grid">
    <table>
        <thead>
            <tr>
                <?php
                $columns = array();
                $obj = Ibe_Map::getTable($this->table);
                if ($obj) {
                    $columns = $obj->getColumns();
                }
                ?>

                <?php foreach ($columns as $column): ?>
                    <?php if (isset($this->modules_params['grid_fks'][$column])): ?>
                        <td><?php echo $this->modules_params['grid_fks'][$column]["label"] ?></td>
                    <?php else: ?>                        
                        <td><?php echo $column ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td colspan='3'>Ações</td>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($this->autores as $autor): ?>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <?php if (isset($this->modules_params['grid_fks'][$column])): ?>
                            <td>
                                <?php 
                                    $table =  $this->modules_params['grid_fks'][$column]["table"];
                                    $field =  $this->modules_params['grid_fks'][$column]["field"]; 
                                    echo $autor->getChild($table)->$field;
                                ?>
                            </td>
                        <?php else: ?>                        
                            <td><?php echo $autor->$column ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td colspan="2">
                        <span class="ibe-form-action-delete">
                            <?php $this->helper->linkapp->_($this->module, $this->controller, 'excluir', 'id/' . $autor->getId(), 'Excluir'); ?>
                        </span>
                        <span class="ibe-form-action-edit">
                            <?php $this->helper->linkapp->_($this->module, $this->controller, $this->action, 'id/' . $autor->getId(), 'Editar'); ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
