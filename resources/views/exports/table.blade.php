                        <div
                                class="tab-pane fade show active"
                                id="ex2-pills-1"
                                role="tabpanel"
                                aria-labelledby="ex2-tab-1"
                                >
                                <h5>Violence Against Children </h5>

                                <div class="table-containers table-responsive">
                                    <table class="table table-bordered" id="my-table" style="border: 1px solid">
                                        <thead>
                                            <tr>
                                                <th class="text-left" style="border-right: 1px solid;text-align:center">Nature of Violance</th>
                                                <th style="border-right: 1px solid;text-align:center">0 - 6</th>
                                                <th style="border-right: 1px solid;text-align:center">7 - 12</th>
                                                <th style="border-right: 1px solid;text-align:center">13 - 18</th>
                                                <th class="break" style="border-right: 1px solid;text-align:center">Age not mentioned</th>
                                                <th style="border-right: 1px solid;text-align:center">Total</th>
                                                <th class="break" style="border-right: 1px solid;text-align:center">Cases Filed</th>
                                                <th style="border-right: 1px solid;text-align:center">Girls</th>
                                                <th style="border-right: 1px solid;text-align:center">Boys</th>
                                                <th class="break" style="border-right: 1px solid;text-align:center">Gender not mentioned</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                            @php 
                                                $ftotal_age_0_6 = 0;
                                                $ftotal_age_7_12 = 0;
                                                $ftotal_age_13_18 = 0;
                                                $ftotal_age_not = 0;
                                                $ftotal_age = 0;
                                                $ftotal_cases_filed = 0;
                                                $ftotal_boys = 0;
                                                $ftotal_girls = 0;
                                                $ftotal_gender_not = 0;
                                            @endphp  

                                            @foreach($violenceList as $key=>$value)

                                                <?php
                                                    $total_age_0_6 = 0;
                                                    $total_age_7_12 = 0;
                                                    $total_age_13_18 = 0;
                                                    $total_age_not = 0;
                                                    $total_age = 0;
                                                    $total_cases_filed = 0;
                                                    $total_boys = 0;
                                                    $total_girls = 0;
                                                    $total_gender_not = 0;
                                                    foreach($data as $output){
                                                        if($value->name==$output->violence_nature){
                                                            $total_age_0_6 += $output->age_0_6;
                                                            $total_age_7_12 += $output->age_7_12;
                                                            $total_age_13_18 += $output->age_13_18;
                                                            $total_age_not += $output->age_not_mentioned;
                                                            $total_age += $output->total_age;
                                                            $total_cases_filed += $output->cases_filed;
                                                            $total_boys += $output->boys;
                                                            $total_girls += $output->girl;
                                                            $total_gender_not += $output->gender_not_mentioned;
                                                        }
                                                    }
                                                ?>
                                                <tr>
                                                    <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$value->name}}</label></td>
                                                    
                                                    
                                               
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_age_0_6 ?? ''}}</td>
                                                      
                                                      
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_age_7_12 ?? ''}}</td>
                                                       
                                                      
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_age_13_18 ?? ''}}</td>
                                                        
                                                        
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_age_not ?? ''}}</td>
                                                        
                                                        
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_age ?? ''}}</td>
                                                        
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_cases_filed ?? ''}}</td>
                                                        
                                                      
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_boys ?? ''}}</td>
                                                      
                                                     
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_girls ?? ''}}</td>
                                             
                                                     
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_gender_not ?? ''}}</td>
                                                            
                                                </tr>
                                                @php
                                                    $ftotal_age_0_6 += $total_age_0_6;
                                                    $ftotal_age_7_12 += $total_age_7_12;
                                                    $ftotal_age_13_18 += $total_age_13_18;
                                                    $ftotal_age_not += $total_age_not;
                                                    $ftotal_age += $total_age;
                                                    $ftotal_cases_filed += $total_cases_filed;
                                                    $ftotal_boys += $total_boys;
                                                    $ftotal_girls += $total_girls;
                                                    $ftotal_gender_not += $total_gender_not;
                                                @endphp
                                                
                                            @endforeach    
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>Total</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_age_0_6}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_age_7_12}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_age_13_18}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_age_not}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_age}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_cases_filed}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_boys}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_girls}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_gender_not}}</label></td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div
                                class="tab-pane fade show active"
                                id="ex2-pills-1"
                                role="tabpanel"
                                aria-labelledby="ex2-tab-1"
                                >
                                <h5>Child Killed</h5>

                                <div class="table-containers table-responsive">
                                    <table class="table table-bordered" id="my-table" style="border: 1px solid">
                                        <thead>
                                            <tr>
                                                <th class="text-left" style="border-right: 1px solid;text-align:center">Nature of Violance</th>
                                                <th style="border-right: 1px solid;text-align:center">0 - 6</th>
                                                <th style="border-right: 1px solid;text-align:center">7 - 12</th>
                                                <th style="border-right: 1px solid;text-align:center">13 - 18</th>
                                                <th class="break" style="border-right: 1px solid;text-align:center">Age not mentioned</th>
                                                <th style="border-right: 1px solid;text-align:center">Total</th>
                                                <th class="break" style="border-right: 1px solid;text-align:center">Cases Filed</th>
                                                <th style="border-right: 1px solid;text-align:center">Girls</th>
                                                <th style="border-right: 1px solid;text-align:center">Boys</th>
                                                <th class="break" style="border-right: 1px solid;text-align:center">Gender not mentioned</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                            @php 
                                                $ftotal_age_0_6 = 0;
                                                $ftotal_age_7_12 = 0;
                                                $ftotal_age_13_18 = 0;
                                                $ftotal_age_not = 0;
                                                $ftotal_age = 0;
                                                $ftotal_cases_filed = 0;
                                                $ftotal_boys = 0;
                                                $ftotal_girls = 0;
                                                $ftotal_gender_not = 0;
                                            @endphp  

                                            @foreach($violenceList2 as $key=>$value)

                                                <?php
                                                    $total_age_0_6 = 0;
                                                    $total_age_7_12 = 0;
                                                    $total_age_13_18 = 0;
                                                    $total_age_not = 0;
                                                    $total_age = 0;
                                                    $total_cases_filed = 0;
                                                    $total_boys = 0;
                                                    $total_girls = 0;
                                                    $total_gender_not = 0;
                                                    foreach($data as $output){
                                                        if($value->name==$output->violence_nature){
                                                            $total_age_0_6 += $output->age_0_6;
                                                            $total_age_7_12 += $output->age_7_12;
                                                            $total_age_13_18 += $output->age_13_18;
                                                            $total_age_not += $output->age_not_mentioned;
                                                            $total_age += $output->total_age;
                                                            $total_cases_filed += $output->cases_filed;
                                                            $total_boys += $output->boys;
                                                            $total_girls += $output->girl;
                                                            $total_gender_not += $output->gender_not_mentioned;
                                                        }
                                                    }
                                                ?>
                                                <tr>
                                                    <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$value->name}}</label></td>
                                                    
                                                    
                                               
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_age_0_6 ?? ''}}</td>
                                                      
                                                      
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_age_7_12 ?? ''}}</td>
                                                       
                                                      
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_age_13_18 ?? ''}}</td>
                                                        
                                                        
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_age_not ?? ''}}</td>
                                                        
                                                        
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_age ?? ''}}</td>
                                                        
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_cases_filed ?? ''}}</td>
                                                        
                                                      
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_boys ?? ''}}</td>
                                                      
                                                     
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_girls ?? ''}}</td>
                                             
                                                     
                                                            <td style="border-right: 1px solid;border-top: 1px solid">{{$total_gender_not ?? ''}}</td>
                                                            
                                                </tr>
                                                @php
                                                    $ftotal_age_0_6 += $total_age_0_6;
                                                    $ftotal_age_7_12 += $total_age_7_12;
                                                    $ftotal_age_13_18 += $total_age_13_18;
                                                    $ftotal_age_not += $total_age_not;
                                                    $ftotal_age += $total_age;
                                                    $ftotal_cases_filed += $total_cases_filed;
                                                    $ftotal_boys += $total_boys;
                                                    $ftotal_girls += $total_girls;
                                                    $ftotal_gender_not += $total_gender_not;
                                                @endphp
                                                
                                            @endforeach    
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>Total</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_age_0_6}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_age_7_12}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_age_13_18}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_age_not}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_age}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_cases_filed}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_boys}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_girls}}</label></td>
                                            <td class="text-left" style="border-right: 1px solid;border-top: 1px solid"><label>{{$ftotal_gender_not}}</label></td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>    
