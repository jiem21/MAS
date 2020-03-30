<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		html,
            body{
                font-family: Verdana, Geneva, sans-serif;
                color: #000;
                font-size: 12px;
            }
            table{
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid black;
                color: #000;
                padding: 10px;
            } 
	</style>
</head>
<body>
	<h4>Hi {{$receiver->name}}</h4>

	<p>Please be inform that you have a pending request for meal allowance</p>

	<table>
            <thead>
                <tr>
                    <td style="width:100px;">Cutoff Date</td>
                    <td>Allowance</td>
                    <td>Total Days</td>
                    <td>Total Employees</td>
                    <td>Total Allowance</td>
                    <td>Allowance Type</td>
                    <td>Prepared By</td>
                    <td>Reviewer</td>
                    <td>Approver</td>
                    <td>Date Generated</td>
                </tr>
            </thead>
            <tbody>
                    <tr>
                       <td>{{ date_con($meal_gen->date_from).' - '. date_con($meal_gen->date_to)}}</td>
                       <td>₱{{ currency($meal_gen->allowance) }}</td>
                       <td>{{ $meal_gen->total_days }}</td>
                       <td>{{ $meal_gen->total_emp }}</td>
                       <td>₱{{ currency($meal_gen->total_allowance) }}</td>
                       <td>{{ allowance_type($meal_gen->allowance_type) }}</td>
                       <td>{{ get_name($meal_gen->prepared_by) }}</td>
                       <td>{{ get_name($meal_gen->reviewed_by) }}</td>
                       <td>{{ get_name($meal_gen->approved_by) }}</td>
                       <td>{{ date("F d,Y h:i:s A",strtotime($meal_gen->prepared_date)) }}</td>
                    </tr>
            </tbody>
        </table>

        <p>
            Regards,
        </p>
        <p>
            <a href="http://192.168.53.222:7070/Pending-For-Approval">Meal Allowance Generation System</a>
        </p>
</body>
</html>