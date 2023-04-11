import React, { useEffect } from "react";
import Modal from "@/Components/Modal";
import { useForm } from "@inertiajs/react";
import Button from "@/Components/Button";
import FormInput from "@/Components/FormInput";
import SizeSelectionInput from '../Size/SelectionInput';
import { toast } from "react-toastify";
export default function FormModal(props) {
    const { modalState, onItemAdd } = props
    const { data, setData, errors, reset, clearErrors } = useForm({
        size_id: '',
        size: '',
        qty:0,
    })

    const handleOnChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? (event.target.checked ? 1 : 0) : event.target.value);
    }
   

    const handleReset = () => {
        modalState.setData(null)
        reset()
        clearErrors()
    }

    const handleClose = () => {
        handleReset()
        modalState.toggle()
    }

    const handleSubmit = () => {
        if(data.size === '' || data.qty ===0) {
            toast.error('Periksa kembali data anda')
            return 
        }
        onItemAdd({
            size_id: data.size.id,
            size: data.size,
            qty:data.qty,
        })
        reset()
        modalState.toggle()
    }

    return (
        <Modal
            isOpen={modalState.isOpen}
            toggle={handleClose}
            title={"Ratio"}
        >
            
            <SizeSelectionInput
             label="Ukuran"
             itemSelected={data.size?.id}
             onItemSelected={(item) => setData('size', item)}
            />
            <FormInput
                type="number"
                name="qty"
                value={data.qty}
                onChange={handleOnChange}
                label="Quantity"
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