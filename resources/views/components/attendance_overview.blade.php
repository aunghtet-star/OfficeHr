<div class="table-responsive">
    <table class="table table-bordered">
        <th>Employee</th>
        @foreach($periods as $period)
        <th class="text-center @if($period->format('D') == 'Sat' || $period->format('D') == 'Sun') alert-danger  @endif"  >{{$period->format('d')}} <br> {{$period->format('D')}}</th>
        @endforeach
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                @foreach($periods as $period)
                    @php
                    $check_in_icon ='';
                    $check_out_icon ='';

                    $office_start_time = $period->format('Y-m-d').' '.$company_setting->office_start_time;
                    $office_end_time = $period->format('Y-m-d').' '.$company_setting->office_end_time;
                    $break_start_time = $period->format('Y-m-d').' '.$company_setting->break_start_time;
                    $break_end_time = $period->format('Y-m-d').' '.$company_setting->office_end_time;
                    
                    $attendance =collect($attendances)->where('user_id',$user->id)->where('date',$period->format('Y-m-d'))->first();
                    if($attendance){
                        if($attendance->check_in_time < $office_start_time){
                        $check_in_icon = '<i class="fas fa-check-circle text-success"></i>';
                        }else if($attendance->check_in_time > $office_start_time && $attendance->check_in_time < $break_start_time){
                            $check_in_icon = '<i class="fas fa-check-circle text-warning"></i>';
                        }else{
                            $check_in_icon = '<i class="fas fa-times-circle text-danger"></i>';
                        }
                        
                        if($attendance->check_out_time > $office_end_time){
                            $check_out_icon = '<i class="fas fa-check-circle text-success"></i>';
                        }else if($attendance->check_out_time < $office_end_time && $attendance->check_out_time > $break_end_time){
                            $check_out_icon = '<i class="fas fa-check-circle text-warning"></i>';
                        }else{
                            $check_out_icon = '<i class="fas fa-times-circle text-danger"></i>';
                        }
                    }

                    
                    @endphp
                <td class="text-center @if($period->format('D') == 'Sat' || $period->format('D') == 'Sun') alert-danger  @endif ">
                    {!! '<div>'.$check_in_icon.'</div>' !!}
                    {!! '<div>'.$check_out_icon.'</div>' !!}
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>