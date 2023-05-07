import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";

import { Button as FlowbiteButton } from "flowbite-react";

import Modal from "@/Components/Modal";
import Button from "@/Components/Button";
import FormInput from "@/Components/FormInput";
import { toast } from "react-toastify";
import { HiOutlineTrash } from "react-icons/hi";

export default function FormModal(props) {
    const { modalState, onItemAdd } = props;
    const { data, setData, reset } = useForm({
        lot_code: "",
        detailFabrics: [{ code: "", qty: 0 }],
    });

    const handleOnChange = (event) => {
        setData(
            event.target.name,
            event.target.type === "checkbox"
                ? event.target.checked
                    ? 1
                    : 0
                : event.target.value
        );
    };

    const handleClose = () => {
        reset();
        modalState.toggle();
    };

    const handleChangeDetailsFabricskValue = (value, index) => {
        setData(
            "detailFabrics",
            data.detailFabrics.map((detail, i) => {
                if (i === index) {
                    return {
                        qty: value,
                    };
                }
                return detail;
            })
        );
    };

    const addDetaiFabric = () => {
        let detail = data.detailFabrics.concat({
            code: "",
            qty: "",
        });
        setData("detailFabrics", detail);
    };

    const removeDetaiFabric = (index) => {
        if (data.detailFabrics.length <= 1) {
            return;
        }
        setData(
            "detailFabrics",
            data.detailFabrics.filter((it, i) => i !== index)
        );
    };

    const handleSubmit = () => {
        if (data.lot_code === "" || +data.detailFabrics.length === 0) {
            toast.error("Periksa kembali data anda");
            return;
        }

        onItemAdd({
            code: data.lot_code,
            detail_fabrics: data.detailFabrics,
        });
        reset();
        modalState.toggle();
    };

    return (
        <Modal
            isOpen={modalState.isOpen}
            toggle={handleClose}
            title={"Item Kain"}
        >
            <FormInput
                name="lot_code"
                value={data.lot_code}
                onChange={handleOnChange}
                label="Kode Lot"
            />

            {data.detailFabrics.map((detail, index) => (
                <div className="grid grid-cols-2 gap-2" key={index}>
                    <div>
                        <FormInput
                            type="number"
                            value={+detail?.qty}
                            onChange={(e) =>
                                handleChangeDetailsFabricskValue(
                                    e.target.value,
                                    index
                                )
                            }
                            label="Quantity"
                        />
                    </div>
                    <div className="mt-4 p-4">
                        <FlowbiteButton
                            outline={true}
                            color="failure"
                            onClick={() => removeDetaiFabric(index)}
                        >
                            <HiOutlineTrash/>
                        </FlowbiteButton>
                    </div>
                </div>
            ))}
            <div>
                <FlowbiteButton outline={true} size={"sm"} onClick={addDetaiFabric}>
                    +
                </FlowbiteButton>
            </div>

            <div className="flex items-center pt-8">
                <Button onClick={handleSubmit}>Tambah</Button>
                <Button onClick={handleClose} type="secondary">
                    Batal
                </Button>
            </div>
        </Modal>
    );
}
