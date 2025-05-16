import { NgClass, NgFor, NgIf } from '@angular/common';
import { Component, ElementRef, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges } from '@angular/core';
import { AbstractControl, FormArray, FormBuilder, FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { AutenticacionService } from '../autenticacion.service';
import { XestorCookiesUsuarioService } from '../xestor-cookies-usuario.service';
import { LigazonsService } from '../ligazons/ligazons.service';
import { duplicadosValidator } from '../shared/validacions/duplicadosValidator';
import { FormValues } from '../shared/interfaces/form-values';
import { Etiqueta } from '../shared/interfaces/etiqueta';
import { GruposService } from '../grupos/grupos.service';

@Component({
  selector: 'app-form-ligazon',
  templateUrl: './form-ligazon.component.html',
  styleUrls: ['./form-ligazon.component.scss'],
  standalone: true,
  imports: [NgIf, ReactiveFormsModule, NgFor, NgClass],
})

export class FormLigazonComponent implements OnInit {
  /**
   * Opción seleccionada. Pásase o valor dende o pai, para poder ensinar un formulario ou outro (cookies, usuario, grupo)
   * @enum 'cookies'|'usuario'|'grupo'
   */
  @Input() opcion: string = ''; // Recibe a opción seleccionada

  /**
   * Modo do formulario. Se non se especifica, estará en modo creación.
   * @enum 'creacion' | 'edicion'
   */
  @Input() modo: string = 'creacion';
  @Input() valoresForm: FormValues = { ligazon_id: 0, id: 0, titulo: '', etiquetas: [], url: '', descricion: '', grupo: 0, apropiado: true }; // Usa o tipo FormValues
  @Input() visibilidade: boolean = false;
  @Output() visibilidadeCambiada = new EventEmitter<boolean>();
  gruposUsuario: any[] = [];

  /**
   * Formulario
   */
  formulario: FormGroup;

  /**
   * Comproba se o usuario está conectado ou non para amosar, condicionalmente, os formularios
   */
  usuarioConectado: boolean = false;

  /**
   * Comproba se é admin
   */
  eAdmin: boolean = false;

  /**
   * Formulario a enviar
   */
  controlsForm;

  /**
   * Cambia a visibilidade do formulario. Serve para o botón "cancelar" cando é aberto nun compoñente pai
   */
  cambiarVisibilidade() {
    this.visibilidade = !this.visibilidade;
    this.visibilidadeCambiada.emit(this.visibilidade);
  }

  /**
   * Validacións por defecto do formulario
   */
  validacions = {
    'titulo': [Validators.required],
    'url': [Validators.required, Validators.pattern(/^[A-Za-z][A-Za-z\d.+-]*:\/*(?:\w+(?::\w+)?@)?[^\s/]+(?::\d+)?(?:\/[\w#!:.?+=&%@\-/]*)?$/)],
    'categoria': [Validators.required],
    'agochado': [Validators.required],
    'apropiado': [Validators.required],
    'etiquetas': [duplicadosValidator()],
    'descricion': [],
    'grupo': [Validators.required],
  }

  /**
   * Controis comúns para os formularios deste compoñente
   */
  controlsComuns = {
    titulo: new FormControl<string>('', this.validacions.titulo),
    url: new FormControl<string>('', this.validacions.url),
    descricion: new FormControl<string>('', this.validacions.descricion),
  }

  /**
   * Controis para o formulario de cookies
   */
  controlsCookies = {
    ...this.controlsComuns,
  }

  /**
   * Controis para o formulario de usuario
   */
  controlsUsuario = {
    ...this.controlsComuns,
    etiquetas: new FormControl<string>('', this.validacions.etiquetas),
    agochado: new FormControl<boolean>(true, this.validacions.agochado),
    apropiado: new FormControl<boolean>(true, this.validacions.apropiado),
  }

  /**
   * Controis para o formulario de grupo
   */
  controlsGrupo = {
    ...this.controlsComuns,
    etiquetas: new FormControl<string>('', this.validacions.etiquetas),
    agochado: new FormControl<boolean>(true, this.validacions.agochado),
    apropiado: new FormControl<boolean>(true, this.validacions.apropiado),
    grupo: new FormControl<number>(-1, this.validacions.grupo),
  }



  get titulo() {
    return this.formulario.controls['titulo'];
  }

  get url() {
    return this.formulario.controls['url'];
  }

  get categoria() {
    return this.formulario.controls['categoria'];
  }

  get agochado() {
    return this.formulario.controls['agochado'];
  }
  /**
   * TODO: Solucionar erro visual no que as seleccións por defecto para checkbox e radiobutton non aparecen marcadas
   * ao cambiar de opción
   */
  get apropiado() {
    return this.formulario.controls['apropiado'];
  }

  get etiquetas(): AbstractControl<string[]> {
    return this.formulario.controls['etiquetas'];
  }

  get descricion() {
    return this.formulario.controls['descricion'];
  }

  get grupo() {
    return this.formulario.controls['grupo'];
  }

  apropiadoChecked: boolean = true;

  /**
   * Comproba se o valor dun control foi modificado polo usuario ou se foi deixado en branco tendo erros
   * @param controlName
   * @param error
   * @returns
   */
  tenErro(controlName: string, error: string): boolean {
    const control = this.formulario.get(controlName);
    return !!(control?.errors?.[error] && (control.touched || control.dirty));
  }

  /**
   * Obtén os erros do servidor
   */
  errosServidor: any;

  constructor(
    private autenticacionService: AutenticacionService,
    private xestorCookies: XestorCookiesUsuarioService,
    private ligazonsService: LigazonsService,
    private gruposService: GruposService,
  ) {
    switch (this.opcion) {
      case 'cookies':
        this.controlsForm = this.controlsCookies;
        break;
      case 'usuario':
        this.controlsForm = this.controlsUsuario;
        break;
      case 'grupo':
        this.controlsForm = this.controlsGrupo;
        break;
      default:
        this.controlsForm = this.controlsCookies;
        break;
    }

    if (this.modo === 'edicion') {
      this.controlsUsuario.titulo.setValue(this.valoresForm.titulo);
      this.controlsUsuario.etiquetas.setValue(this.separarArrayConComas(this.valoresForm.etiquetas)); // Converte as etiquetas

      this.controlsForm.url.setValue(this.valoresForm.url);
      this.controlsForm.url.disable();

      this.controlsForm.descricion.setValue(this.valoresForm.descricion);
      this.formulario = new FormGroup(this.controlsForm);
    } else {
      this.formulario = new FormGroup(this.controlsForm);
    }

    if (this.modo === 'creacion' && this.opcion == 'grupo') {
      this.obterGruposUsuarioCreadorConectado();
      //console.log(this.opcion)
    }


    this.autenticacionService.usuarioConectado$.subscribe((estado) => {
      this.usuarioConectado = estado;
    });

    this.autenticacionService.eAdmin$.subscribe((estado) => {
      this.eAdmin = estado;
    });
  }

  obterGruposUsuarioCreadorConectado() {
    if (this.gruposUsuario.length > 0) return;
    this.gruposService.obterGruposUsuarioCreadorConectado().subscribe({
      next: (value) => {
        console.table(value)
        this.gruposUsuario = value.grupos || [];
      },
      error: (err) => {
        console.error(err)
      },
    })
  }

  ngOnInit() {
    if (this.modo == 'edicion') {
      this.controlsUsuario.etiquetas.setValue(this.separarArrayConComas(this.valoresForm['etiquetas']));
      this.controlsForm.url.setValue(this.valoresForm['url']);
      this.controlsForm.descricion.setValue(this.valoresForm['descricion']);
      this.formulario = new FormGroup(this.controlsForm);
    } else {
      this.formulario = new FormGroup(this.controlsForm);
    }
  }

  engadirControls() {
    this.formulario.reset();
  }

  obterControlsOpcion() {
    let controlsOpcion;
    switch (this.opcion) {
      case 'cookies':
        controlsOpcion = this.controlsCookies;
        break;
      case 'usuario':
        controlsOpcion = this.controlsUsuario;
        break;
      case 'grupo':
        controlsOpcion = this.controlsGrupo;
        break;
      default:
        controlsOpcion = this.controlsCookies;
        break;
    }
    return controlsOpcion;
  }

  /**
   * Resetea o formulario e engade ou quita controis según a opción seleccionada
   */
  xerarFormGroup(): void {
    this.formulario.reset();
    this.controlsForm = this.obterControlsOpcion();
    this.formulario = new FormGroup(this.controlsForm);
  }

  /**
   * Converte as etiquetas do formulario de tipo string a array
   */
  convertirEtiquetas(): void {
    const etiquetasValor = this.formulario.get('etiquetas')?.value;
    if (typeof etiquetasValor === 'string' && etiquetasValor.trim() !== '') {
      const etiquetasArray = etiquetasValor.split(',').map((etiqueta) => etiqueta.trim());
      this.formulario.patchValue({ etiquetas: etiquetasArray });
    } else {
      this.formulario.patchValue({ etiquetas: '' });
    }
  }

  /**
   * Converte
   */
  convertirEtiquetasEdicion(): void {
    const etiquetasValor = this.formulario.get('etiquetas')?.value;
    if (typeof etiquetasValor === 'string' && etiquetasValor.trim() !== '') {
      const etiquetasArray = etiquetasValor.split(',').map(titulo => ({ titulo: titulo.trim() } as Etiqueta)); // Converte en obxectos Etiqueta
      this.formulario.patchValue({ etiquetas: etiquetasArray });
    } else {
      this.formulario.patchValue({ etiquetas: [] });
    }
  }


  separarArrayConComas(arrayObxectivo: Etiqueta[]): string {
    return arrayObxectivo.map(etiqueta => etiqueta.titulo).join(','); // Extrae os títulos e úneos por comas
  }





  gardarLigazon(formulario: FormGroup) {
    if (this.opcion == 'cookies') {
      this.xestorCookies.engadirLigazon(formulario.value);
    }
    if (this.opcion == 'usuario') {
      if (this.modo == 'edicion') {
        this.convertirEtiquetasEdicion();
        console.log('Etiquetas do formulario:');
        console.table(this.valoresForm.etiquetas);

        this.formulario.addControl('ligazon_id', new FormControl(this.valoresForm.ligazon_id));
        this.formulario.patchValue({ 'ligazon_id': this.valoresForm.ligazon_id });

        this.ligazonsService.actualizarLigazonUsuario(formulario, 'usuario', this.valoresForm.ligazon_id).subscribe({
          next: (value) => {
            alert('A ligazón foi actualizada con éxito!');
          },
          error: (err) => {
            this.errosServidor = err;
            console.table(this.errosServidor)
            alert(this.errosServidor)
          },
        });
      } else {
        this.convertirEtiquetas();
        console.log(formulario.value['etiquetas']);
        console.log('A opción é usuario');

        this.ligazonsService.crearLigazon(formulario, 'usuario').subscribe({
          next: (value) => {
            console.log(value);
            alert('A ligazón foi gardada con éxito!');
          },
          error: (err) => {
            console.log(err);
          },
        });

      }
    } else if (this.opcion == "grupo") {
      this.convertirEtiquetas();

      this.formulario.addControl('grupo_id', new FormControl(this.formulario.controls['grupo'].value));
      this.formulario.patchValue({ 'grupo_id': this.formulario.controls['grupo'].value });

      this.ligazonsService.crearLigazon(formulario, 'grupo').subscribe({
        next: (value) => {
          console.log(value);
          alert('A ligazón foi gardada con éxito!');
        },
        error: (err) => {
          console.log(err);
        },
      });

    }
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['opcion']) {
      this.xerarFormGroup();
      this.opcion = changes['opcion'].currentValue
      this.obterGruposUsuarioCreadorConectado()
    }
    if (changes['valoresForm'] || this.visibilidade) {
      this.controlsUsuario.titulo.setValue(this.valoresForm['titulo']);
      this.controlsUsuario.etiquetas.setValue(this.separarArrayConComas(this.valoresForm['etiquetas']));
      this.controlsUsuario.apropiado.setValue(this.valoresForm['apropiado'] ? this.valoresForm['apropiado'] : true);
      this.apropiadoChecked = true;
      this.controlsForm.url.setValue(this.valoresForm.url);
      this.controlsForm.url.disable();
      this.controlsForm.descricion.setValue(this.valoresForm.descricion);
      this.formulario = new FormGroup(this.controlsForm);
    } else {
      this.formulario = new FormGroup(this.controlsForm);
    }
  }
}
