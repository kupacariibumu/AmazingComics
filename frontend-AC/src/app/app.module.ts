import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from "@angular/forms";
import { routing, appRountingProviders } from "./app.routing";
import { HttpClientModule } from "@angular/common/http";
import { SweetAlert2Module } from "@sweetalert2/ngx-sweetalert2";

import { AppComponent } from './app.component';
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { HomeComponent } from './components/home/home.component';
import { ErrorComponent } from './components/error/error.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    RegisterComponent,
    HomeComponent,
    ErrorComponent
  ],
  imports: [
    BrowserModule,
    routing,
    FormsModule,
    HttpClientModule,
    SweetAlert2Module.forRoot()
  ],
  providers: [
    appRountingProviders
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
