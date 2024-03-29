import React, { useEffect } from "react";
import Modal from "@/Components/Modal";
import { useForm } from "@inertiajs/react";
import Button from "@/Components/Button";
import FormInput from "@/Components/FormInput";

import { isEmpty } from "lodash";
import TextArea from "@/Components/TextArea";

export default function FormModal(props) {
    const { modalState } = props
    const { data, setData, post, put, processing, errors, reset, clearErrors } = useForm({
        name: '',
        email: '',
        phone: '',
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
        const buyer = modalState.data
        if(buyer !== null) {
            put(route('buyer.update', buyer), {
                onSuccess: () => handleClose(),
            })
            return
        } 
        post(route('buyer.store'), {
            onSuccess: () => handleClose()
        })
    }

    useEffect(() => {
        const buyer = modalState.data
        if (isEmpty(buyer) === false) {
            setData({
                name: buyer.name,
                phone: buyer.phone,
                email: buyer.email,
                description: buyer.description,
            })
            return 
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            toggle={handleClose}
            title={"Pembeli"}
        >
            <FormInput
                name="name"
                value={data.name}
                onChange={handleOnChange}
                label="name"
                error={errors.name}
            />
            <FormInput
                name="phone"
                value={data.phone}
                onChange={handleOnChange}
                label="kontak"
                error={errors.phone}
            />
            <FormInput
                name="email"
                value={data.email}
                onChange={handleOnChange}
                label="email"
                error={errors.email}
            />
            <TextArea
                name="description"
                value={data.description}
                onChange={handleOnChange}
                label="deskripsi"
                error={errors.description}
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