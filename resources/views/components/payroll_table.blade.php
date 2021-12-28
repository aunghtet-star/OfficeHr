<div class="table-responsive">
    <table class="table table-bordered">
        <th>Employee</th>
        <th class="text-center">Role</th>
        <th class="text-center">DayofMonth</th>
        <th class="text-center">WorkingDay</th>
        <th class="text-center">Off Day</th>
        <th class="text-center">AttendanceDay</th>
        <th class="text-center">Leave</th>
        <th class="text-center">PerDay (MMK)</th>
        <th class="text-center">Total (MMK)</th>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{implode(',',$user->roles->pluck('name')->toArray())}}</td>
                <td>{{$dayofMonth}}</td>
                <td>{{$workingDay}}</td>
                <td>{{$dayofMonth - $workingDay}}</td>
                @php
                $attendanceDay ='0';
                $salary = collect($user->salaries)->where('month', $month)->where('year', $year)->first();
                
                $perday = $salary ? $salary->amount / $workingDay : 0;

                
                @endphp 

                

                @foreach($periods as $period)
                    @php

                    $office_start_time = $period->format('Y-m-d').' '.$company_setting->office_start_time;
                    $office_end_time = $period->format('Y-m-d').' '.$company_setting->office_end_time;
                    $break_start_time = $period->format('Y-m-d').' '.$company_setting->break_start_time;
                    $break_end_time = $period->format('Y-m-d').' '.$company_setting->office_end_time;
                    
                    $attendance =collect($attendances)->where('user_id',$user->id)->where('date',$period->format('Y-m-d'))->first();
                    if($attendance){
                        if($attendance->check_in_time < $office_start_time){
                            $attendanceDay +='0.5';
                        }else if($attendance->check_in_time > $office_start_time && $attendance->check_in_time < $break_start_time){
                            $attendanceDay +='0.5';
                        }else{
                            $attendanceDay +='0';
                        }
                        
                        if($attendance->check_out_time > $office_end_time){
                            $attendanceDay +='0.5';
                        }else if($attendance->check_out_time < $office_end_time && $attendance->check_out_time > $break_end_time){
                            $attendanceDay +='0.5';
                        }else{
                            $attendanceDay +='0';;
                        }
                    }

                    
                    @endphp
                    @endforeach

                <td class="text-center">
                    {!! '<div>'.$attendanceDay.'</div>' !!}
                </td>
                <td class="text-center">{{$workingDay - $attendanceDay}}</td>
                <td class="text-center">{{number_format($perday)  }}</td>
                <td class="text-center">{{ number_format($perday * $attendanceDay) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>