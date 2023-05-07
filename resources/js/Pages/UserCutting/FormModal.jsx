import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";

import Modal from "@/Components/Modal";
import Button from "@/Components/Button";
import FormInput from "@/Components/FormInput";
import { toast } from "react-toastify";
import SeletedInputDetailFabric from "../Fabric/SeletedInputDetailFabric";

export default function FormModal(props) {
    const { modalState, onItemAdd,detailFabric,ratio_qty } = props
    const { data, setData, reset } = useForm({
        detail_fabric: null,
        quantity: 0,
        total_qty:0,
    })

    const handleOnChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? (event.target.checked ? 1 : 0) : event.target.value);
    }

    const handleClose = () => {
        reset()
        modalState.toggle()
    }

    const handleSubmit = () => {
        
        if(data.detail_fabric === '' || data.quantity === 0) {
            toast.error('Periksa kembali data anda')
            return 
        }
        onItemAdd({
            quantity:data.quantity,
            detail_fabric: data.detail_fabric,
            total_qty:data.quantity*ratio_qty
        })
        reset()
        modalState.toggle()
    }

    return (
        <Modal
            isOpen={modalState.isOpen}
            toggle={handleClose}
            title={"Kain"}
        >
            <div>
                <SeletedInputDetailFabric
                 label="Kain"
                 itemSelected={data.detail_fabric}
                 onItemSelected={(fabric) => setData('detail_fabric',fabric)}
                 listitems={detailFabric}
                />
            </div>
            <FormInput
                type="number"
                name="quantity"
                value={data.quantity}
                onChange={handleOnChange}
                label="Jumlah Lembar"
            />
            <div className="flex items-center">
                <Button
                    onClick={handleSubmit}
                >
                    Tambah
                </Button>
                <Button
                    onClick={handleClose}
                    type="secondary"
                >
                    Batal
                </Button>
            </div>
        </Modal>
    )
}