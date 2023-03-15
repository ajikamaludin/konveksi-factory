import React, { useEffect } from "react";
import Modal from "@/Components/Modal";
import { useForm } from "@inertiajs/react";
import Button from "@/Components/Button";
import FormInput from "@/Components/FormInput";

import { isEmpty } from "lodash";

export default function FormModal(props) {
    const { modalState } = props
    const { data, setData, post, put, processing, errors, reset, clearErrors } = useForm({
        name: '',
        description: '',
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
        const brand = modalState.data
        if(brand !== null) {
            put(route('brand.update', brand), {
                onSuccess: () => handleClose(),
            })
            return
        } 
        post(route('brand.store'), {
            onSuccess: () => handleClose()
        })
    }

    useEffect(() => {
        const brand = modalState.data
        if (isEmpty(brand) === false) {
            setData({
                name: brand.name,
                description: brand.description,
            })
            return 
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            toggle={handleClose}
            title={"Brand"}
        >
            <FormInput
                name="name"
                value={data.name}
                onChange={handleOnChange}
                label="nama"
                error={errors.name}
            />
            <div className="flex items-center">
                <Button
                    onClick={handleSubmit}
                    processing={processing} 
                >
                    Simpan
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