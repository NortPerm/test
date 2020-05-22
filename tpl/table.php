<table id="myTable" class="smt" width=100%>
                <tr>
                    <th width="30px"></th>  
                    <th width="30px">№</th>  
                    <th width="120px">Дата обращения</th>  
                    <th width="200px">ФИО заявителя</th>  
                    <th>Описание обращения</th>  
                </tr>
                <?php foreach ($data as $row) { ?>
                    <tr id="row<?php echo $row['id']; ?>">
                        <td><input id="check_<?php echo $row['id']; ?>" type="checkbox"/></td>  
                        <td><?php echo $row['id']; ?></td>  
                        <td><?php echo $row['appeal_date']; ?></td>  
                        <td><?php echo $row['person_name']; ?></td>  
                        <td><?php echo $row['appeal_text']; ?></td>  
                    </tr>
                <?php } ?>
</table>