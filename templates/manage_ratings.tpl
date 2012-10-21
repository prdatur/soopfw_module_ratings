<%$pager|unescape%>
<table class="ui-widget ui-widget-content " style="margin-top: 10px;" cellspacing="0" cellpadding="0" border="0">
	<thead class="ui-widget-header">
		<tr>
			<td style="width: 25px;text-align: center;"><input type="checkbox" id="dmySelectAll" class="input_checkbox"/></td>
			<td style="text-align: left;"><%t key='User'%></td>
			<td style="text-align: left;width: 120px;"><%t key='Date'%></td>
			<td style="text-align: left;width: 40px;"><%t key='Rating'%></td>
			<td style="width: 50px;text-align: center;"></td>
		</tr>
	</thead>
	<tbody>
	<%foreach from=$entries item=row%>
		<tr row="<%$row.widget_id%>|<%$row.user_identifier%>">
			<td style="text-align: center;"><input type="checkbox" name="selected[]" value="<%$row.widget_id%>|<%$row.user_identifier%>" id="dmySelect_<%$row.widget_id%>|<%$row.user_identifier%>" class="dmySelect input_checkbox"/></td>
			<td style="text-align: left;"><%$row.user|unescape%></td>
			<td style="text-align: left;"><%$row.date|format_date:'d.m.Y H:i:s'%></td>
			<td style="text-align: left;"><%$row.rating%></td>
			<td style="text-align: center;">
				<img src="/1x1_spacer.gif" class="ui-icon-soopfw ui-icon-soopfw-cancel linkedElement dmyDelete" did="<%$row.widget_id%>|<%$row.user_identifier%>" title="<%t key='delete?'%>" alt="<%t key='delete?'%>">
			</td>
		</tr>
	<%foreachelse%>
	<tr>
		<td colSpan="10" style="font-style: italic; text-align:center;">
			<%t key='Nothing found'%>
		</td>
	</tr>
	</tbody>
	<%/foreach%>
</table>
<div class="multi_action">
	&nbsp;&nbsp;&nbsp;<img src="/templates/images/multi_choose_arrow.png">
	<select id="multi_action">
		<option value=""><%t key='selected:'%></option>
		<option value="delete"><%t key='delete?'%></option>
	</select>
</div>