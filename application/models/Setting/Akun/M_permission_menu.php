<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_permission_menu extends CI_Model
{
    public function get()
    {
        return $this->db
                        ->select("
                                level.id,
                                level.name as level,
                                isnull(SUM(CAST([read] as INT)),0) as [read],
                                isnull(SUM(CAST([create] as INT)),0) as [create],
                                isnull(SUM(CAST([update] as INT)),0) as [update],
                                isnull(SUM(CAST(permission_menu.[delete] as INT)),0) as [delete]                                
                                ")
                        ->from("menu")
                        ->join("level",
                                "level.id = level.id")
                        ->join("permission_menu",
                                "permission_menu.menu_id = menu.id
                                AND permission_menu.level_id = level.id",
                                "LEFT")
                        ->group_by("level.id,level.name")
                        ->get()->result();    
    }
    public function get_group_user(){
        return $this->db
                        ->select("
                            group_user.id,
                            project.name as project,
                            jabatan.name as jabatan,
                            user.name as user,
                            group_user.description    
                        ")
                        ->from("group_user")
                        ->join("project",
                                "project.id = group_user.project_id")
                        ->join("user",
                                "user.id = group_user.user_id")
                        ->join("jabatan",
                                "jabatan.id = group_user.jabatan_id")
                        ->where("group_user.delete",0)
                        ->get()->result();
    }
    public function get_level(){
        return $this->db
                        ->from("level")
                        ->where("delete",0)
                        ->get()->result();    
    }
    public function get_permission_menu_by_id($id){
        return $this->db
                        ->select("
                                menu.id,
                                menu.name as menu,
                                isnull([read],0) as [read],
                                isnull([create],0) as [create],
                                isnull([update],0) as [update],
                                isnull(permission_menu.[delete],0) as [delete],
                                permission_menu.description
                                ")
                        ->from("menu")
                        ->join("level",
                                "level.id = level.id")
                        ->join("permission_menu",
                                "permission_menu.menu_id = menu.id
                                AND permission_menu.level_id = level.id",
                                "LEFT")
                        ->where("level.id",$id)
                        ->where("(url != '' and url is not null)")

                        ->order_by("menu.id")
                        ->get()->result();    
    }
    public function save($data){
        $return = (object)[];
        $return->message = "Data Permission Menu Gagal di Tambah";
        $return->status = false;

        $id = $data['id'];
        $data = $data['data'];
        
        $this->db->where("level_id",$id);
        $this->db->delete("permission_menu");

        $this->db->trans_start();

        foreach ($data as $v) {
            $dataRow = [
                "menu_id"   => $v['menu_id'],
                "level_id"  => $id,
                "read"      => $v['read'],
                "create"    => $v['create'],
                "update"    => $v['update'],
                "delete"    => $v['delete']
            ];        
            $this->db->insert("permission_menu",$dataRow);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE){
            $return->message = "Data Permission Menu Berhasil di Edit";
            $return->status = true;
        }
        
        return $return;

    }
    public function delete($data){
    
        $return = (object)[];
        $return->message = "Data Group Level Gagal di Hapus";
        $return->status = false;

        $this->db->trans_start();
        $this->db->where("id",$data["id"]);
        $this->db->update("group_user_level",['delete'=> 1]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE){
            $return->message = "Data Group Level Berhasil di Hapus";
            $return->status = true;
        }

        return $return;
    }
   
}
