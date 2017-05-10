interface FocuserInterface
{
    readonly Position: number;

    readonly TargetPosition: number;

    getPosition();

    setPosition(target: number);
}

export default FocuserInterface;