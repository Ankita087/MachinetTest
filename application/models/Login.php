<?php
class Login extends CI_Model {  
  
  public function log_in_correctly() {  

      $this->db->where('email', $this->input->post('email'));  // ise email uthana chaiye
      $this->db->where('password', $this->input->post('password'));  // ise ppass databse nem to likha ni yahn isko kase pta chl ra ki? ok abi dekjhte h kuj gdbd h
      $query = $this->db->get('machineTestTable');  // iske bare m sure nhi hu it must be table name

      if ($query->num_rows() == 1)  //if query successfull
      {  
          $this->db->where('email', $this->input->post('email'));

        $this->db->set('lastLoginDate', date('Y-m-d H:i:s'));
        $this->db->update('machineTestTable'); 
          return true; 
      } else {  
          return false;  
      }  

  }  

    
}  
?> 