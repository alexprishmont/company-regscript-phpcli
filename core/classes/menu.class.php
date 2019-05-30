<?php
class Menu {
    // PRIVATE
    private $menu;
    private $handle;
    private $line;

    private function getLine() {
        return trim(fgets($this->handle));
    }
    
    private function clear() {
        for ($i = 0; $i < 50; $i++)
            echo "\r\n";
    }

    private function reshow() {
        $this->show();
        $this->choose();
    }

    // PUBLIC
    public function __construct() {
        $this->menu = [
            "Add company",
            "Edit company",
            "Delete company",
            "Exit"
        ];
        $this->handle = fopen("php://stdin", "r");
    }

    public function show() {
        for ($i = 0; $i < sizeof($this->menu); $i++) {
            $listitem = $i + 1;
            echo $listitem.". ".$this->menu[$i]."\n";
        }
        echo "Write a list item's number below: ";
    }

    public function choose() {
        $line = $this->getLine();
        
       /* if ($line - 1 > sizeof($menu) || $line <= 0) {
            $this->clear();
            echo "Such list item does not exist!\n";
            $this->reshow();
        }*/

        switch($this->menu[$line - 1]) {
            case 'Add company': {
                $newcompany = new Company();
                $data = [];
                $comment = "";

                $this->clear();

                echo "Write company name: ";
                $ln = $this->getLine();
                $data[] = $ln;

                echo "Write company registration code: ";
                $ln = $this->getLine();
                $data[] = $ln;

                echo "Write company email: ";
                $ln = $this->getLine();
                $data[] = $ln;      

                echo "Write company phone: ";
                $ln = $this->getLine();
                $data[] = $ln;   

                echo "Add comment: ";
                $ln = $this->getLine();
                $comment = $ln;  

                if ($newcompany->unique_email($data[2]) && $newcompany->valid_phone($data[3])) {
                    $newcompany->add($data);
                    $newcompany->comment($comment);
                    $newcompany->store();
                }

                $this->reshow();
                break;
            }
            case 'Edit company': {
                $company = new Company();

                $this->clear();
                
                echo "Write company name which you want edit: ";
                $name = $this->getLine();

                if ($company->find_company_by_name($name)) {
                    $this->clear();
                    echo "Company with name [".$name."] successfully loaded! Now you can edit it.\n";

                    echo "Which information you want edit: \n 1. Name\n 2. Registration code\n 3. EMAIL\n 4. Phone\n 5. Back\n";
                    $this->line = $this->getLine();

                    if (!is_numeric($this->line) || ($this->line > 5 && $this->line < 1)) {
                        echo "Your entered menu item not valid.\n";

                        echo "Which information you want edit: \n 1. Name\n 2. Registration code\n 3. EMAIL\n 4. Phone\n 5. Back\n";
                        $this->line = $this->getLine();
                    }

                    switch ($this->line) {
                        case '1': {
                            $this->clear();
                            
                            echo "Write new company [".$name."] name: ";
                            $newname = $this->getLine();
                            $company->update_company_data($newname, "name");
                            
                            break;
                        }
                        case '2': {
                            $this->clear();
                            
                            echo "Write new company [".$name."] registration code: ";
                            $regcode = $this->getLine();
                            $company->update_company_data($regcode, "registration_code");
                        
                            break;
                        }
                        case '3': {
                            $this->clear();
                            
                            echo "Write new company [".$name."] email: ";
                            $email = $this->getLine();

                            if ($company->unique_email($email))
                                $company->update_company_data($email, "email");

                            break;
                        } 
                        case '4': {
                            $this->clear();
                            
                            echo "Write new company [".$name."] phone: ";
                            $phone = $this->getLine();

                            if ($company->valid_phone($phone))
                                $company->update_company_data($phone, "phone");
                            
                            break;
                        }   
                        case '5': {
                            $this->clear();
                            $this->reshow();
                            break;
                        }
                    }
                }

                $this->reshow();
                break;
            }
            case 'Delete company': {
                $company = new Company();

                $this->clear();

                echo "Write company name which you want delete: \n";
                $name = $this->getLine();

                if ($company->find_company_by_name($name)) $company->delete_company();
                
                $this->reshow(); 
                break;
            }

            case 'Exit': {
                exit;
                break;
            }
        }

    }
}

?>