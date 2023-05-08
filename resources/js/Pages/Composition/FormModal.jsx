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
        const composition = modalState.data
        if(composition !== null) {
            put(route('composition.update', composition), {
                onSuccess: () => handleClose(),
            })
            return
        } 
        post(route('composition.store'), {
            onSuccess: () => handleClose()
        })
    }

    useEffect(() => {
        const composition = modalState.data
        if (isEmpty(composition) === false) {
            setData({
                name: composition.name,
               
            })
            return 
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            toggle={handleClose}
            title={"Komposisi"}
        >
            <FormInput
                name="name"
                value={data.name}
                onChange={handleOnChange}
                label="Komposisi"
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