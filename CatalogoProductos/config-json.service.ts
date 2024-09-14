import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ConfigJsonService {

  CONFIG_PATH = environment.servidor.TAG_SERVIDOR_CONFIG;

  constructor(private httpClient: HttpClient) { }
  getLoadConfigJson(): Observable<any> {
    return this.httpClient.get<any>(`${this.CONFIG_PATH}/config.php`);
  }
}
