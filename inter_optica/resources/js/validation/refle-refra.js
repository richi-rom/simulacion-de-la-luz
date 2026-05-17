export const angleRefle = {
    angle(value) {
        if (Number(value)%10 != 0 && Number(value) >= 0 && Number(value) <= 90 || value == undefined ||  value == '') {
            return 'Debe ser número múltiplo de 10 y de [0,90]';
        }else{
            return true;
        }
    }
}


export const coordinatesRefle = {
    coordX(value) {
        if (Number(value) >= 0 && Number(value) <= 3 && value != '') {
            return true;            
        }else{
            return 'Debe ser un número de [0, 3]';
        }
    },
    coordY(value) {
        if (Number(value) >= 0 && Number(value) <= 2 && value != '') {
            return true;            
        }else{
            return 'Debe ser un número de [0, 2]';
        }
    },
    coordZ(value) {
        if (Number(value) >= 0 && Number(value) <= 2.5 && value != '') {
            return true;            
        }else{
            return 'Debe ser un número de [0, 2.5]';
        }
    }
}
