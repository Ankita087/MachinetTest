<?php
class Login extends CI_Model {  
  
  public function log_in_correctly() {  

      $this->db->where('email', $this->input->post('email'));
      $this->db->where('password', $this->input->post('password'));
      $query = $this->db->get('machineTestTable');

      if ($query->num_rows() == 1)
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