
<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { route } from 'ziggy-js'
import useVuelidate from '@vuelidate/core';
import { required, email, minLength, numeric  } from '@vuelidate/validators';
import { useForm, useField } from 'vee-validate'
import { angleRefle, coordinatesRefle } from '@/validation/refle-refra';
import Plotly from 'plotly.js-dist';
import { nextTick } from 'vue';





const radios = ref(true)
const imagenM = ref('')
const logo = ref('')
const loa = ref('')
const imagenReference = ref(true);
const simulationTrue = ref(false)
const valuesObject = ref({name: 'Agua', idr: '1.33',})
const valuesSpace = ref({ name: 'Vacío', idr: '1',})

const items = [
    {
      name: 'Vacío',
      idr: '1',
    },
    {
      name: 'Hielo',
      idr: '1.31',
    },
    {
      name: 'Agua',
      idr: '1.33',
    },
    {
      name: 'Acetona',
      idr: '1.36',
    },
    {
      name: 'Safiro',
      idr: '1.77',
    },
    {
      name: 'Diamante',
      idr: '2.417',
    },
  ]


// Formulario con angulo
const {  handleSubmit: handleSubmitAngle, resetForm: resetFormAngle } = useForm({
    validationSchema: angleRefle,
});
const angleform = {
    angle: useField('angle'),
}
//Formulario con coordenadas

const { handleSubmit: handleSubmitCoord, resetForm: resetFormCoord } = useForm({
    validationSchema: coordinatesRefle,
});
const coordform = {
    coordX: useField('coordX'),
    coordY: useField('coordY'),
    coordZ: useField('coordZ'),
}


const resetEveryFomrs = () => {
    resetFormCoord();
    resetFormAngle();
}


const getImage =  (imagen) => {
    resetEveryFomrs();
    imagenM.value = '';
    let im = 0
    //if (imagen) {
      //  im = 1
    //} 
    axios.get(route('getImage', { imagen: im }))
    .then(res => {
        console.log(res.data['IMAGEN'])
        imagenM.value = res.data['IMAGEN']
        logo.value = res.data['LOGO']
        loa.value = res.data['LOA']

    })
    .catch(err => {
      console.error(err)
    })
}
getImage(radios.value);


const createSimulationAngle = handleSubmitAngle((dataForm) => {

    imagenReference.value = false
    simulationTrue.value = false
    imagenM.value = '';
    axios.post(
        route('simulation',
        {
            idrObject: valuesObject.value.idr,
            idrSpace: valuesSpace.value.idr,
            angle: dataForm.angle,
            coordX: '2',
            coordY: '2',
            coordZ: '2',
            mod: '0'

        })

    ).then(res => {
      //console.log(res['data']['resultado'])
        let figura = JSON.parse(res['data']['resultado']);
    
        nextTick(() => {
            const el = document.getElementById('grafica-referencia');
            if (!el) {
                simulationTrue.value = false
                console.error("No existe #grafica-referencia");
                return;
            }

            Plotly.newPlot(el, figura.data, figura.layout, { responsive: true });
            simulationTrue.value = true
        });


    })
    .catch(err => {
      console.error(err)
    })

});


const createSimulationCoord = handleSubmitCoord((dataForm) => {

    imagenReference.value = false
    simulationTrue.value = false
    imagenM.value = '';
    axios.post(
        route('simulation',
        {
            idrObject: valuesObject.value.idr,
            idrSpace: valuesSpace.value.idr,
            angle: '2',
            coordX: dataForm.coordX,
            coordY: dataForm.coordY,
            coordZ: dataForm.coordZ,
            mod: '1'

        })

    ).then(res => {

        //console.log(res['data']['resultado'])
        let figura = JSON.parse(res['data']['resultado']);
    
        nextTick(() => {
            const el = document.getElementById('grafica-referencia');
            if (!el) {
                simulationTrue.value = false
                console.error("No existe #grafica-referencia");
                return;
            }

            Plotly.newPlot(el, figura.data, figura.layout, { responsive: true });
            simulationTrue.value = true
        });


    })
    .catch(err => {
      console.error(err)
    })

    

});


function itemProps (item) {
    return {
      title: item.idr,
      subtitle: item.name,
    }
  }




</script>
<template>
    <div>
        <v-row style="margin-bottom: 0.8%;">
            <div style="margin-right:7%;">
                <img src="/storage/app/public/Logo-UV.jpg" alt="imagen" style="max-width: 150px;" />
            </div>

            <v-col style="margin-right:5%; margin-top: 5%;">
                <h1 style="width: 100%;">Reflexión y Refracción de la Luz</h1>
            </v-col>

            <div style="margin-right:5%;">
                <img src="/storage/app/public/LOA.jpeg" alt="imagen" style="max-width: 250px;" />
            </div>

        </v-row>

        <p style="margin-bottom: 1%; font-size: larger; background-color: azure; padding: 0.5%; border-radius: 5%;">El objetivo de este simulador es modelar y visualizar los fenómenos de reflexión y refracción de la luz al interactuar con superficies planas paralelas</p>
        
        <v-row>
            <v-col>
                <v-container>
                    <v-row>
                        <vol-col>
                            <v-container fluid>
                                <p style="font-weight: bold;">Selecciona la opción de creación del rayo incidente</p>
                                <v-radio-group v-model="radios" style="margin-top: 1%">
                                    <v-radio label="Dado con ángulo" :value="true" ></v-radio>
                                    <v-radio label="Dado con coordenadas" :value="false"></v-radio>
                                </v-radio-group>
                            </v-container>
                        </vol-col>

                    </v-row>

                    <div v-if="radios" style="padding-right: 45%">
                        <v-col>
                            <p style="margin-bottom:5%; font-weight: bold;">*Debe ser múltiplo de 10</p>
                            <v-text-field
                                label="Ángulo de incidencia de [0,90]"
                                variant="outlined"
                                v-model="angleform.angle.value.value"
                                :error-messages="angleform.angle.errorMessage.value"
                            ></v-text-field>
                            <div  v-if="angleform.angle.errorMessage.value != ''">
                                <p style="color: red">
                                    {{ message }}
                                </p>
                            </div>
                        </v-col>
                    </div>
                    <div v-else style="padding-right: 50%">
                        <v-col>
                            <v-text-field
                                label="Coordenada en X∈[0,3]"
                                variant="outlined"
                                v-model="coordform.coordX.value.value"
                                :error-messages="coordform.coordX.errorMessage.value"
                            ></v-text-field>
                            <div  v-if="coordform.coordX.errorMessage.value != ''" style="margin-bottom:3%">
                                <p style="color: red">
                                    {{ message }}
                                </p>
                            </div>
                            <v-text-field
                                label="Coordenada en Y∈[0,2]"
                                variant="outlined"
                                v-model="coordform.coordY.value.value"
                                :error-messages="coordform.coordY.errorMessage.value"
                            ></v-text-field>
                            <div  v-if="coordform.coordY.errorMessage.value != ''" style="margin-bottom:3%">
                                <p style="color: red">
                                    {{ message }}
                                </p>
                            </div>
                            <v-text-field
                                label="Coordenada en Z∈[0, 2.5]"
                                variant="outlined"
                                v-model="coordform.coordZ.value.value"
                                :error-messages="coordform.coordZ.errorMessage.value"
                            ></v-text-field>
                            <div  v-if="coordform.coordZ.errorMessage.value != ''">
                                <p style="color: red">
                                    {{ message }}
                                </p>
                            </div>
                        </v-col>
                    </div>

                    <div style="padding-right: 50%">
                        <v-container fluid>
                            <p style="padding-bottom: 1%; font-weight: bold;">Indice de refracción del medio de propagación</p> 
                            <v-select
                                style="padding-bottom: 2%"
                                :item-props="itemProps"
                                :items="items"
                                item-value="idr"
                                v-model="valuesObject"
                                :hint="`${valuesObject.name}`"
                                variant="outlined"
                                persistent-hint
                                return-object
                                single-line
                            ></v-select>

                            <p style="padding-bottom: 1%; font-weight: bold;">Indice de refracción del medio indicente</p> 
                            <v-select
                                style="padding-bottom: 2%"
                                :item-props="itemProps"
                                :items="items"
                                item-value="idr"
                                v-model="valuesSpace"
                                :hint="`${valuesSpace.name}`"
                                variant="outlined"
                                persistent-hint
                                return-object
                                single-line
                            ></v-select>
                        </v-container>
                    </div>
                </v-container>
            </v-col>
            <v-col>
                <v-col  style="padding-right:30%;" >
                    <div>
                        <v-col v-if="radios">
                            <v-btn @click="createSimulationAngle" color="blue" size="large" >Crear simulación</v-btn>
                        </v-col>
                        <v-col v-else>
                            <v-btn @click="createSimulationCoord" color="blue" size="large" >Crear simulación</v-btn>
                        </v-col>
                     </div>
                </v-col>
                <p v-if="imagenReference" class="p-parrafo">Imagen de referencia</p>
                <p v-if="simulationTrue == false && imagenM == ''" class="p-parrafo">Cargando...</p>
                <div id="grafica-referencia"></div>
                
                <div v-if="imagenReference">

                    <div v-if="imagenM == ''">
                        <v-progress-circular
                            :size="50"
                            color="primary"
                            indeterminate
                        ></v-progress-circular>
                    </div>
                    <div v-else>
                        <img src="/storage/app/public/RefleRefraLuzAngle.png" alt="imagen" style="max-width: 400px;" />
                    </div>
                
                </div>
            </v-col>
          
        </v-row>
    </div>
</template>



<style>
    .p-parrafo {
        margin-left: 4%;
        margin-bottom: 3%;
        
    }

</style>

