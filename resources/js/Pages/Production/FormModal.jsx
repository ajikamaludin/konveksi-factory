import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";

import Modal from "@/Components/Modal";
import Button from "@/Components/Button";
import FormInput from "@/Components/FormInput";
import SizeSeletionInput from '../Size/SelectionInput';
import ColorSeletionInput from '../Color/SelectionInput';
import { toast } from "react-toastify";

export default function FormModal(props) {
    const { modalState, onItemAdd } = props
    const { data, setData, reset } = useForm({
        size: '',
        color: '',
        target_quantity: 0,
        lock: 0
    })

    const handleOnChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? (event.target.checked ? 1 : 0) : event.target.value);
    }

    const handleClose = () => {
        reset()
        modalState.toggle()
    }

    const handleSubmit = () => {
        if(data.size === '' ||data.color===''|| +data.target_quantity === 0) {
            toast.error('Periksa kembali data anda')
            return 
        }
        onItemAdd({
            size_id: data.size.id,
            size: data.size,
            color_id: data?.color.id,
            color: data?.color,
            target_quantity:data.target_quantity,
            lock: data.lock,
        })
        reset()
        modalState.toggle()
    }

    return (
        <Modal
            isOpen={modalState.isOpen}
            toggle={handleClose}
            title={"Item Artikel"}
        >
            <div>
                <ColorSeletionInput
                    label="Warna"
                    itemSelected={data.color?.id}
                    onItemSelected={(item) => setData('color', item)}
                />
            </div>
            <div className='mb-2'>
                <SizeSeletionInput
                    label="Ukuran"
                    itemSelected={data.size?.id}
                    onItemSelected={(item) => setData('size', item)}
                />
            </div>
            <FormInput
                type="number"
                name="target_quantity"
                value={data.target_quantity}
                onChange={handleOnChange}
                label="Total PO"
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