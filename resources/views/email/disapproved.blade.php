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

	<p>Please be inform that your request for meal allowance has been disapproved</p>

	<table>
            <thead>
                <tr>
                    <td style="width:100px;">Cutoff Date</td>
                    <td>Allowance Type</td>
                    <td>Reviewer</td>
                    <td>Reason/Remarks</td>
                    <td>Date</td>
                    <td>Approver</td>
                    <td>Reason/Remarks</td>
                    <td>Date</td>
                </tr>
            </thead>
            <tbody>
                    <tr>
                       <td>{{ date_con($meal_gen->date_from).' - '. date_con($meal_gen->date_to)}}</td>
                       <td>{{ allowance_type($meal_gen->allowance_type) }}</td>
                       <td>{{ get_name($meal_gen->reviewed_by) }}</td>
                       <td>{{ $meal_gen->reviewer_reason }}</td>
                       <td>{{ date("F d,Y h:i:s A",strtotime($meal_gen->reviewed_date)) }}</td>
                       <td>{{ get_name($meal_gen->approved_by) }}</td>
                       <td>{{ $meal_gen->approver_reason }}</td>
                       <td>{{ ($meal_gen->approved_date == NULL) ? '' : date("F d,Y h:i: A",strtotime($meal_gen->approved_date)) }}</td>
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