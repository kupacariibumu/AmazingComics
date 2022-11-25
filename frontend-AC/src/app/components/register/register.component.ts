import { Component, OnInit } from '@angular/core';
import { User } from "../../models/user";
import { UserService } from "../../services/user.service";
import Swal from 'sweetalert2/dist/sweetalert2.js';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
  providers: [UserService],
})
export class RegisterComponent implements OnInit {

  public page_title: string;
  public user: User;

  constructor(
    private _userService: UserService
  ) {
    this.page_title = "Registrate";
    this.user = new User(1, '', '', 'ROLE_USER', '', '', '', '');
  }

  ngOnInit(): void {
  }

  showModal() {}

  onSubmit(form: any) {

    this._userService.register(this.user).subscribe(
      response => {
        form.reset();

        Swal.fire({
          title: 'Listo!',
          text: response.message,
          icon: 'success',
          confirmButtonText: 'Ok'
        });

      },
      error => {
        Swal.fire({
          title: 'ERROR',
          text: error.error.message,
          icon: 'error',
          confirmButtonText: 'Ok'
        });

        // console.log(<any>error);
        // console.log(error.error.message);
      }
    )

  }

}
